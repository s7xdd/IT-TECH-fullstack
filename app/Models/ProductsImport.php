<?php

namespace App\Models;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\ProductSeo;
use App\Models\User;
use App\Models\ProductTabs;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Auth;
use Carbon\Carbon;
use File;
use Image;
use Mpdf\Tag\Tr;
use Storage;


//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $rows = 0;

    private $year = 0;
    private $month = 0;

    public function __construct()
    {
        $this->year = Carbon::now()->year;
        $this->month =  Carbon::now()->format('m');
    }

    public function collection(Collection $rows)
    {
        // dd($rows);
        $brands = Brand::all();
        $categories = Category::all();
        foreach ($rows as $row) {

            $sku = $this->cleanSKU($row['product_code']); 

            $imageArray = array_filter($row->toArray(), function($value,$key) {
                return (strpos($key, 'url') === 0 && trim($value) !== '' );
            }, ARRAY_FILTER_USE_BOTH);
            // print_r($imageArray);
            // // print_r($row);
            // echo '******************************************************************************************';
            $tabArray = array_filter($row->toArray(), function($key) {
                return strpos($key, 'tab') === 0;
            }, ARRAY_FILTER_USE_KEY);
            
            $productTabs = [];
            $productDescription = (isset($row['description'])) ? $row['description'] : NULL;
            
            $sku = $this->cleanSKU($row['product_code']);

            $brand = null;
            $parent_id = 0;

            if (isset($row['brand'])) {
                $newBrand = trim($row['brand']);
                $brand = $brands->where('name',$newBrand)->first();
                if($brand){
                    $brand->id;
                }else{
                    $brand = Brand::firstOrCreate(['name' => $newBrand]);

                    // Insert slug into brand_translations table
                    BrandTranslation::updateOrCreate(
                        [
                            'brand_id' => $brand->id, 
                            'lang' => 'en' // Change this based on your localization
                        ],
                        [
                            'name' => $newBrand,
                            'slug' => \Str::slug($newBrand)
                        ]
                    );
                }
            }

            if (isset($row['category'])) {
                $category = explode(':', $row['category']);
                foreach ($category as $key => $cat) {
                    $cat = trim($cat);
                    $c = $categories->where('name', 'LIKE', $cat)->where(
                        'parent_id',
                        $parent_id
                    )->first();

                    if ($c) {
                        $parent_id = $c->id;
                    } else {
                        $c_new = Category::firstOrCreate(['name' => $cat,
                            'parent_id' => $parent_id,
                            'level' => $key + 1
                        ]);

                        CategoryTranslation::updateOrCreate(
                            [
                                'category_id' => $c_new->id, 
                                'lang' => 'en' // Change this based on your localization
                            ],
                            [
                                'name' => $cat,
                                'slug' => $this->categorySlug($cat)
                            ]
                        );

                        $categories->push($c_new);
                        $parent_id = $c_new->id;
                    }
                }
            }
          

            $productId = Product::where(['sku' => $sku])->get()->first();
            if ($productId) {
                if (isset($row['product_name'])) {
                    $productId->name = trim($row['product_name']);
                }
                
                if (isset($row['category'])) {
                    $productId->category_id = $parent_id;
                }
                if (isset($brand)) {
                    $productId->brand_id = $brand->id;
                }
                if (isset($row['vat'])) {
                    $productId->vat = $row['vat'];
                }
                if (isset($row['video_provider'])) {
                    $productId->video_provider = $row['video_provider'];
                }

                if (isset($row['video_link'])) {
                    $productId->video_link = $row['video_link'];
                }

                if (isset($row['price'])) {
                    $productId->unit_price = $row['price'];
                }
               
                if (isset($row['return_available'])) {
                    $productId->return_refund = $row['return_available'];
                }

                if (isset($row['status'])) {
                    $productId->published = $row['status'];
                }
            
                if (isset($row['discount_price']) && isset($row['discount_type']) && isset($row['discount_start_date']) && isset($row['discount_end_date'])) {
                    $productId->discount = $row['discount_price'];

                    if(strtolower($row['discount_type']) == 'percentage'){
                        $productId->discount_type = 'percent';
                    }elseif(strtolower($row['discount_type']) == 'amount'){
                        $productId->discount_type = 'amount';
                    }
                    
                    if (is_numeric($row['discount_start_date']) && is_numeric($row['discount_end_date'])) {
                        $start = Date::excelToDateTimeObject($row['discount_start_date'])->format('Y-m-d 00:00:00');
                        $end = Date::excelToDateTimeObject($row['discount_end_date'])->format('Y-m-d 23:59:00');
                        
                        $discount_start_date = strtotime($start);
                        $discount_end_date = strtotime($end);
    
                        $productId->discount_start_date = $discount_start_date;
                        $productId->discount_end_date = $discount_end_date;
                    }
                }else{
                    $productId->discount = NULL;
                    $productId->discount_type = NULL;
                    $productId->discount_start_date = NULL;
                    $productId->discount_end_date = NULL;
                }
                $productId->updated_by = Auth::user()->id;
                $productId->save();
            } else {
                $discount_price = $discount_type = $discount_type = $discount_start_date = $discount_end_date = NULL;
                if (isset($row['discount_price']) && isset($row['discount_type']) && isset($row['discount_start_date']) && isset($row['discount_end_date'])) {
                    $discount_price = $row['discount_price'];

                    if(strtolower($row['discount_type']) == 'percentage'){
                        $discount_type = 'percent';
                    }elseif(strtolower($row['discount_type']) == 'amount'){
                        $discount_type = 'amount';
                    }
                    if (is_numeric($row['discount_start_date']) && is_numeric($row['discount_end_date'])) {
                        $start = Date::excelToDateTimeObject($row['discount_start_date'])->format('Y-m-d 00:00:00');
                        $end = Date::excelToDateTimeObject($row['discount_end_date'])->format('Y-m-d 23:59:00');
    
                        $discount_start_date = strtotime($start);
                        $discount_end_date = strtotime($end);
                    }
                }
               

                $productId = Product::create([
                    'sku' => $sku,
                    'name' => trim($row['product_name']) ?? '',
                    'video_provider' => trim($row['video_provider']) ?? '',
                    'video_link' => trim($row['video_link']) ?? '',
                    'category_id' => $parent_id,
                    'brand_id' => $brand ? $brand->id : 0,
                    'vat' => $row['vat'] ?? 0,
                    'unit_price' => $row['price'] ?? 1,
                    'return_refund' => $row['return_available'] ?? 0,
                    'published' => $row['status'] ?? 0,
                    'discount' => $discount_price,
                    'discount_type' => $discount_type,
                    'discount_start_date' => $discount_start_date,
                    'discount_end_date' => $discount_end_date,
                    'slug' => $this->productSlug(trim($row['product_name'])),
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);
            }

            $product_translation                = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $productId->id]);
            $product_translation->name          = trim($row['product_name']) ?? '';
            $product_translation->tags          = trim($row['keywords']) ?? '';
            $product_translation->description   = trim($row['description']) ?? '';
            $product_translation->save();

            $seo = ProductSeo::firstOrNew(['lang' => env('DEFAULT_LANGUAGE', 'en'), 'product_id' => $productId->id]);

            $seo->meta_title            = trim($row['meta_title']) ?? '';
            $seo->meta_description      = trim($row['meta_description']) ?? '';
            $seo->meta_keywords         = trim($row['meta_keywords']) ?? '';
            $seo->og_title              = trim($row['og_title']) ?? '';
            $seo->og_description        = trim($row['og_description']) ?? '';
            $seo->twitter_title         = trim($row['twitter_title']) ?? '';
            $seo->twitter_description   = trim($row['twitter_description']) ?? '';

            if (trim($row['meta_title']) == null) {
                $seo->meta_title        = $productId->name;
            }
            if (trim($row['og_title']) == null) {
                $seo->og_title          = $productId->name;
            }
            if (trim($row['twitter_title']) == null) {
                $seo->twitter_title     = $productId->name;
            }
            $seo->save();

            $mainImage = $galleryImage = $mainImageUploaded = $galleryImageUploaded ='';
            if(!empty($imageArray)){
                if(isset($imageArray['url_1'])){
                    $mainImage = $imageArray['url_1'];
                    unset($imageArray['url_1']);
                }
                $galleryImage = $imageArray;
            }

            if($mainImage != ''){
                $mainImageUploaded = $this->downloadAndResizeImage($mainImage, $sku, true);
            }

            if (!empty($galleryImage)) {
                $galleryImage = $this->downloadGallery($galleryImage, $sku);
                $galleryImageUploaded = implode(',', $galleryImage);
            }

            if ($mainImageUploaded) {
                $productId->thumbnail_img = $mainImageUploaded;
            }
            if ($galleryImageUploaded) {
                $productId->photos = $galleryImageUploaded;
            }
            $productId->save();
            if ($productId) {
                ProductStock::updateOrCreate([
                    'product_id' => $productId->id,
                    'sku' => $sku,
                ], [
                    'qty' => (isset($row['quantity']) && $row['quantity'] !== NULL) ? $row['quantity'] : 2,
                    'price' => $row['price'] ?? 1,
                    'variant' => '',
                ]);

                if(!empty($tabArray)){
                    foreach($tabArray as $key=>$tba){
                        $key = Str::after($key,'tab');
                        if($tba != null && $tba != ''){
                            $productTabs[] = [
                                'product_id' => $productId->id,
                                'heading'      => ucfirst(str_replace('_', ' ',$key)),
                                'content'   => $tba,
                                'lang' => env('DEFAULT_LANGUAGE', 'en')
                            ];
                        }
                    }
                }
             
                if(!empty($productTabs)){
                    ProductTabs::where('product_id', $productId->id)->delete();
                    ProductTabs::insert($productTabs);
                }
            }
        }
        flash('Products imported successfully')->success();
    }

    public function model(array $row)
    {
        $this->rows++;
    }

    public function getRowCount()
    {
        return $this->rows;
    }

    public function productSlug($name)
    {
        $slug = Str::slug($name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        return $slug;
    }
    public function categorySlug($name)
    {
        $slug = Str::slug($name, '-');
        $same_slug_count = CategoryTranslation::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        return $slug;
    }

    public function rules(): array
    {
        return [
            // 'product_code' => function ($attribute, $value, $onFailure) {
            //     if (!is_numeric($value)) {
            //         $onFailure('Unit price is not numeric');
            //     }
            // }
            'product_code' => 'required',
        ];
    }

    public function downloadGallery($urls, $sku)
    {
        $i = 0;
        $data = [];
        foreach ($urls as $index => $url) {
            // $url = base_path('product_images').'/'.$url;
            $response = Http::withoutVerifying()->head($url);
            if ($response->ok()) {
                $data[] = $this->downloadAndResizeImage($url, $sku, false, $i + 1);
                $i++;
            }
        }
        return $data;
    }

    public function downloadAndResizeImage($imageUrl, $sku, $mainImage = false, $count = 1, $update = false)
    {                                                   
        $data_url = '';

        try {
            $ext = Str::of($imageUrl)->afterLast('.');
            $path = 'products/'. $sku . '/';

            if ($mainImage) {
                $filename = $path . $sku . '.' . $ext;
            } else {
                $n = $sku . '_gallery_' .  $count;
                $filename = $path . $n . '.' . $ext;
            }

            $response = Http::withoutVerifying()->head($imageUrl);
            if ($response->ok()) {
                // Download the image from the given URL
               
                $imageContents = file_get_contents($imageUrl);

                // Save the original image in the storage folder
                Storage::disk('public')->put($filename, $imageContents);
                $data_url = Storage::url($filename);
                // Create an Intervention Image instance for the downloaded image
                $image = Image::make($imageContents);

                $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                if ($extension === 'png') {
                    $image->encode('png'); // Ensures transparency is maintained
                }else{
                    $image->encode('webp', 80);
                }
                
                // Resize and save three additional copies of the image with different sizes
                $sizes = config('app.img_sizes'); // Specify the desired sizes in pixels

                foreach ($sizes as $size) {
                    $resizedImage = $image->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    if ($mainImage) {
                        $filename2 = $path . $sku . "_{$size}px" . '.' . $ext;
                    } else {
                        $n = $sku . '_gallery_' .  $count . "_{$size}px";
                        $filename2 = $path . $n . '.' . $ext;
                    }

                    // Save the resized image in the storage folder
                    Storage::disk('public')->put($filename2, $resizedImage->encode('jpg'));

                    // $data_url[] = Storage::url($filename2);
                }
            }
        } catch (Exception $e) {
        }

        return $data_url;
    }

  
    public function cleanSKU($sku)
    {
        $sku = trim($sku);
        $sku = preg_replace('/[^a-zA-Z0-9\-\_]/i', '', $sku);
        return $sku;
    }
}
