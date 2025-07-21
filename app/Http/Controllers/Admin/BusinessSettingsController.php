<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\Page;
use App\Models\PageTranslation;
use Artisan;
use DB;
use Illuminate\Support\Facades\Artisan as FacadesArtisan;

// use CoreComponentRepository;

class BusinessSettingsController extends Controller
{
    public function general_setting(Request $request)
    {
        // CoreComponentRepository::instantiateShopRepository();
        //CoreComponentRepository::initializeCache();
        return view('backend.setup_configurations.general_settings');
    }

    public function update(Request $request)
    {

        if (!empty($request->types)) {
            foreach ($request->types as $key => $type) {
                if ($type == 'site_name') {
                    $this->overWriteEnvFile('APP_NAME', $request[$type]);
                }
                if ($type == 'timezone') {
                    $this->overWriteEnvFile('APP_TIMEZONE', $request[$type]);
                } else {
                    $lang = $request->has('lang') ? $request->lang : NULL;;
                    if (gettype($type) == 'array') {
                        $lang = array_key_first($type);
                        $type = $type[$lang];
                        $business_settings = BusinessSetting::where('type', $type)->where('lang', $lang)->first();
                    } else {
                        $business_settings = BusinessSetting::where('type', $type)->first();
                    }


                    if ($business_settings != null) {
                        if (gettype($request[$type]) == 'array') {
                            $business_settings->value = json_encode($request[$type]);
                        } else {
                            $business_settings->value = $request[$type];
                        }
                        $business_settings->lang = $lang;
                        $business_settings->save();
                    } else {
                        $business_settings = new BusinessSetting;
                        $business_settings->type = $type;
                        if (gettype($request[$type]) == 'array') {
                            $business_settings->value = json_encode($request[$type]);
                        } else {
                            $business_settings->value = $request[$type];
                        }
                        $business_settings->lang = $lang;
                        $business_settings->save();
                    }
                }
            }
        }

        $id = $request->has('page_id') ? $request->page_id : null;

        if ($id != null) {
            $page = Page::findOrFail($id);
            if ($page) {
                $page_translation                           = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
                if ($request->has('title')) {
                    $page_translation->title                = $request->title;
                }
                if ($request->has('title1')) {
                    $page_translation->title1               = $request->title1;
                }
                if ($request->has('title2')) {
                    $page_translation->title2               = $request->title2;
                }
                if ($request->has('title3')) {
                    $page_translation->title3               = $request->title3;
                }
                if ($request->has('content')) {
                    $page_translation->content              = $request->content;
                }
                if ($request->has('sub_title')) {
                    $page_translation->sub_title            = $request->sub_title;
                }
                if ($request->has('heading1')) {
                    $page_translation->heading1             = $request->heading1;
                }
                if ($request->has('content1')) {
                    $page_translation->content1             = $request->content1;
                }
                if ($request->has('heading2')) {
                    $page_translation->heading2             = $request->heading2;
                }
                if ($request->has('content2')) {
                    $page_translation->content2             = $request->content2;
                }
                if ($request->has('heading3')) {
                    $page_translation->heading3             = $request->heading3;
                }
                if ($request->has('content3')) {
                    $page_translation->content3             = $request->content3;
                }
                if ($request->has('content4')) {
                    $page_translation->content4             = $request->content4;
                }
                if ($request->has('content5')) {
                    $page_translation->content5             = $request->content5;
                }
                if ($request->has('heading4')) {
                    $page_translation->heading4                 = $request->heading4;
                }
                if ($request->has('heading5')) {
                    $page_translation->heading5             = $request->heading5;
                }
                if ($request->has('heading6')) {
                    $page_translation->heading6             = $request->heading6;
                }
                if ($request->has('heading7')) {
                    $page_translation->heading7             = $request->heading7;
                }
                if ($request->has('heading8')) {
                    $page_translation->heading8             = $request->heading8;
                }
                if ($request->has('heading9')) {
                    $page_translation->heading9             = $request->heading9;
                }

                if ($request->has('meta_title')) {
                    $page_translation->meta_title           = $request->meta_title;
                }

                if ($request->has('meta_description')) {
                    $page_translation->meta_description     = $request->meta_description;
                }
                if ($request->has('og_title')) {
                    $page_translation->og_title             = $request->og_title;
                }
                if ($request->has('og_description')) {
                    $page_translation->og_description       = $request->og_description;
                }
                if ($request->has('twitter_title')) {
                    $page_translation->twitter_title        = $request->twitter_title;
                }
                if ($request->has('twitter_description')) {
                    $page_translation->twitter_description  = $request->twitter_description;
                }
                if ($request->has('keywords')) {
                    $page_translation->keywords             = $request->keywords;
                }
                if ($request->has('image1')) {
                    $page_translation->image1               = $request->image1;
                }

                $page_translation->save();

                if ($request->has('image1')) {
                    $page->image1   = $request->image1;
                }

                if ($request->has('image2')) {
                    $page->image2   = $request->image2;
                }

                if ($request->has('image3')) {
                    $page->image3   = $request->image3;
                }
                if ($request->has('image4')) {
                    $page->image4   = $request->image4;
                }
                if ($request->has('image5')) {
                    $page->image5   = $request->image5;
                }
                if ($request->has('image6')) {
                    $page->image6   = $request->image6;
                }
                if ($request->has('image7')) {
                    $page->image7   = $request->image7;
                }
                if ($request->has('image8')) {
                    $page->image8   = $request->image8;
                }
                if ($request->has('image9')) {
                    $page->image9   = $request->image9;
                }
                if ($request->has('image10')) {
                    $page->image   = $request->image10;
                }

                $page->save();
            }

            $photos = [];
            if ($request->hasfile('images')) {
                if ($page->image == null) {
                    $count = 1;
                    $old_photos = [];
                } else {
                    $old_photos = explode(',', $page->image);
                    $count = count($old_photos) + 1;
                }

                foreach ($request->file('images') as $key => $file) {
                    $photos[] = uploadImage('page', $file, 'image_' . $count + $key);
                }
                $page->image = implode(',', array_merge($old_photos, $photos));
                $page->save();
            }

            if ($request->hasfile('image')) {
                $photo = uploadImage('page', $request->image, 'image_1');
                $page->image = $photo;
                $page->save();
            }
        }

        FacadesArtisan::call('cache:clear');

        flash(trans("messages.settings") . ' ' . trans("messages.updated_msg"))->success();
        return back();
    }



    public function shipping_configuration(Request $request)
    {
        return view('backend.general_settings.settings');
    }

    public function shipping_configuration_update(Request $request)
    {
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        $business_settings->value = $request[$request->type];
        $business_settings->save();

        Artisan::call('cache:clear');
        return back();
    }

    public function freeshipping_settings(Request $request)
    {

        BusinessSetting::updateOrCreate([
            'type' => 'free_shipping_status'
        ], [
            'value' => $request->free_shipping_status ? 1 : 0
        ]);

        BusinessSetting::updateOrCreate([
            'type' => 'free_shipping_min_amount'
        ], [
            'value' => $request->free_shipping_min_amount ?? 0
        ]);

        BusinessSetting::updateOrCreate([
            'type' => 'default_shipping_amount'
        ], [
            'value' => $request->default_shipping_amount ?? 0
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }

    public function return_settings(Request $request)
    {
        BusinessSetting::updateOrCreate([
            'type' => 'default_return_time'
        ], [
            'value' => $request->default_return_time ?? 0
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }

    public function service_settings(Request $request)
    {
        BusinessSetting::updateOrCreate([
            'type' => 'default_service_whatsapp'
        ], [
            'value' => $request->default_service_whatsapp ?? NULL
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }

    function handleSettingImages($fields, $prefix = 'business-setting', $images_fields = ['image', 'image1', 'image2', 'image3', 'image4', 'image5', 'image6'])
    {
        if (!is_array($fields)) return $fields;

        $isAssoc = array_keys($fields) !== range(0, count($fields) - 1);
        $processed = [];

        if ($isAssoc) {
            foreach ($fields as $key => $value) {
                if (in_array($key, $images_fields) && $value instanceof \Illuminate\Http\UploadedFile) {
                    $processed[$key] = uploadImage($prefix, $value);
                } elseif (is_array($value)) {
                    $processed[$key] = handleSettingImages($value, $prefix, $images_fields);
                } else {
                    $processed[$key] = $value;
                }
            }
        } else {
            foreach ($fields as $row) {
                $processed[] = handleSettingImages($row, $prefix, $images_fields);
            }
        }
        return $processed;
    }
}
