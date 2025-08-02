// import './bootstrap';

import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import $ from 'jquery';

import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

window.$ = window.jQuery = $;
window.toastr = toastr; // Make toastr globally available

window.Alpine = Alpine;
Alpine.start();

// --- Header Scroll Behavior ---
(() => {
    let lastScrollY = 0;
    let isStyled = false; // Tracks current header style state
    const header = document.getElementById("main-header");
    const logo = document.getElementById("header-logo");
    const nav = document.getElementById("header-nav");
    const btn = document.getElementById("header-btn");
    const hamburger = document.getElementById("hamburger-menu");

    // Early exit if essential header elements are missing
    if (!header || !logo || !nav || !btn || !hamburger) {
        console.warn(
            "Header elements not found. Skipping header scroll behavior initialization."
        );
        return;
    }

    const logoWhite = logo.dataset.logoWhite;
    const logoColored = logo.dataset.logoColored;

    const isIndexPage =
        ["/", "/index.html"].includes(window.location.pathname) ||
        window.location.pathname.endsWith("/index.html");

    // Unified function to apply styles
    const applyHeaderStyles = (scrolled) => {
        if (scrolled === isStyled) return; // Only apply if style needs to change

        header.classList.toggle("bg-transparent", !scrolled);
        header.classList.toggle("text-white", !scrolled);
        header.classList.toggle("py-7", !scrolled);
        header.classList.toggle("!bg-white", scrolled);
        header.classList.toggle("shadow", scrolled);
        header.classList.toggle("text-gray-900", scrolled);
        header.classList.toggle("py-3.5", scrolled);

        logo.src = scrolled ? logoColored : logoWhite;

        nav.querySelectorAll("a, button").forEach((el) => {
            el.classList.toggle("hover:text-gray-300", !scrolled);
            el.classList.toggle("hover:text-blue-700", scrolled);
            el.classList.toggle("text-gray-800", scrolled);
        });

        btn.classList.toggle("bg-white", !scrolled);
        btn.classList.toggle("text-blue-800", !scrolled);
        btn.classList.toggle("bg-[--primary]", scrolled);
        btn.classList.toggle("text-white", scrolled);
        btn.classList.toggle("hover:bg-blue-900", scrolled);

        hamburger.classList.toggle("bg-white", !scrolled);
        hamburger.classList.toggle("bg-opacity-10", !scrolled);
        hamburger.classList.toggle("hover:bg-opacity-20", !scrolled);
        hamburger.classList.toggle("bg-gray-100", scrolled);
        hamburger.classList.toggle("hover:bg-gray-200", scrolled);

        hamburger.querySelectorAll("span").forEach((span) => {
            span.classList.toggle("bg-white", !scrolled);
            span.classList.toggle("group-hover:bg-sky-400", !scrolled);
            span.classList.toggle("bg-gray-800", scrolled);
            span.classList.toggle("group-hover:bg-blue-700", scrolled);
        });

        isStyled = scrolled;
    };

    // Initialize header style based on whether the page is index.html
    applyHeaderStyles(!isIndexPage);

    window.addEventListener("scroll", () => {
        const currentScrollY = window.scrollY;

        // Determine if scrolled beyond the threshold for styling
        const shouldBeScrolled = currentScrollY > 100;

        // Apply styles if necessary for all pages
        applyHeaderStyles(shouldBeScrolled || !isIndexPage);

        // Header hide/show on scroll for index page and other pages
        if (currentScrollY > lastScrollY && currentScrollY > 50) {
            // Scrolling down and past a small threshold
            header.style.transform = "translateY(-100%)";
        } else if (currentScrollY < lastScrollY) {
            // Scrolling up
            header.style.transform = "";
        }

        lastScrollY = currentScrollY;
    });
})();

// --- Smooth Random Blink for about-bg-img ---
// Use GSAP's built-in functionality for repeating animations
function smoothRandomBlink() {
    const img = document.getElementById("about-bg-img");
    if (!img) return;

    const animate = () => {
        const targetOpacity = Math.random() * 0.5 + 0.5; // Opacity between 0.5 and 1.0
        const duration = 1.2;
        const delay = Math.random() * 2 + 1; // Delay between 1 and 3 seconds

        gsap.to(img, {
            opacity: targetOpacity,
            duration: duration,
            ease: "power2.inOut",
            onComplete: () => {
                setTimeout(animate, delay * 1000); // Convert delay to milliseconds
            },
        });
    };
    animate(); // Start the first animation
}
smoothRandomBlink();

// --- Newsletter Email Input Logic ---
(() => {
    const emailInput = document.getElementById("newsletter_email");
    const sendBtn = document.getElementById("newsletter-send");

    if (!emailInput || !sendBtn) return;

    // Set initial state of the button
    sendBtn.style.opacity = "0";
    sendBtn.style.pointerEvents = "none";
    sendBtn.style.transform = "translateY(-50%) translateX(1rem)"; // Initial hidden state

    const toggleSendButton = () => {
        const hasText = emailInput.value.trim().length > 0;
        gsap.to(sendBtn, {
            opacity: hasText ? 1 : 0,
            x: hasText ? 0 : "1rem",
            pointerEvents: hasText ? "auto" : "none",
            duration: 0.3,
            ease: "power2.out",
        });
    };

    emailInput.addEventListener("input", toggleSendButton);
})();

// --- Responsive Resizing for Hero and Section Headings ---
// Debounce the resize event for performance
let resizeTimeout;
function resizeHeroWrapper() {
    const wrapper = document.getElementById("slider-media");
    const heroHeading = document.getElementById("heroText");
    const additionalHeadings = document.querySelectorAll(".responsive-heading");

    const windowWidth = window.innerWidth;
    const windowHeight = window.innerHeight;

    // === Adjust Wrapper Height ===
    if (wrapper) {
        let newHeight;

        // At 1920px width, height should be 550px.
        // Gradually reduce height as width decreases, down to a minimum (e.g., 320px width).
        const minWidth = 320;
        const maxWidth = 1920;
        const minHeight = 320; // Minimum height for very small screens
        const maxHeight = 550; // Height at 1920px width

        // Linear interpolation based on window width
        const widthFactor = Math.min(
            Math.max((windowWidth - minWidth) / (maxWidth - minWidth), 0),
            1
        );
        newHeight = minHeight + widthFactor * (maxHeight - minHeight);

        wrapper.style.height = `${newHeight}px`;
        wrapper.style.display = "";

        // Reset contentWrapper styles
        const contentWrapper = document.querySelector(".content-wrapper");
        if (contentWrapper) {
            contentWrapper.style.position = "";
            contentWrapper.style.top = "";
            contentWrapper.style.left = "";
            contentWrapper.style.transform = "";
            contentWrapper.style.width = "";
            contentWrapper.style.textAlign = "";
        }

        // Reset heroHeading size if it was modified
        if (heroHeading) {
            heroHeading.style.fontSize = "";
            heroHeading.style.lineHeight = "";
        }
    }

    // === Replace or append "space-y-3 xl:space-y-5" with "space-y-8"
    const heroContent = document.getElementById("hero-content");
    if (heroContent) {
        let cls = heroContent.className;
        if (cls.includes("space-y-3") || cls.includes("xl:space-y-5")) {
            cls = cls
                .replace(/space-y-3/g, "")
                .replace(/xl:space-y-5/g, "")
                .trim();
        }
        if (!cls.includes("space-y-8")) {
            cls = `${cls} space-y-8`.trim();
        }
        heroContent.className = cls.replace(/\s+/g, " ");
    }

    // === Scaling Configuration ===
    const config = {
        minWidth: 320,
        maxWidth: 1600,
        minHeight: 400,
        maxHeight: 1000,

        heroFont: { min: 26, max: 75 },
        heroLine: { min: 28, max: 75 },

        sectionFont: { min: 32, max: 45 },
        sectionLine: { min: 38, max: 50 },
    };

    const widthFactor = Math.min(
        Math.max(
            (windowWidth - config.minWidth) /
                (config.maxWidth - config.minWidth),
            0
        ),
        1
    );
    const heightFactor = Math.min(
        Math.max(
            (windowHeight - config.minHeight) /
                (config.maxHeight - config.minHeight),
            0
        ),
        1
    );
    const scaleFactor = Math.min(widthFactor, heightFactor);

    // === Hero Heading Font & Line Height ===
    if (heroHeading) {
        const heroFontSize =
            config.heroFont.min +
            scaleFactor * (config.heroFont.max - config.heroFont.min);
        const heroLineHeight =
            config.heroLine.min +
            scaleFactor * (config.heroLine.max - config.heroLine.min);

        heroHeading.style.fontSize = `${heroFontSize}px`;
        heroHeading.style.lineHeight = `${heroLineHeight}px`;
    }

    // === Section Headings ===
    const sectionFontSize =
        config.sectionFont.min +
        scaleFactor * (config.sectionFont.max - config.sectionFont.min);
    const sectionLineHeight =
        config.sectionLine.min +
        scaleFactor * (config.sectionLine.max - config.sectionLine.min);

    additionalHeadings.forEach((heading) => {
        heading.style.fontSize = `${sectionFontSize}px`;
        heading.style.lineHeight = `${sectionLineHeight}px`;
    });
}

// Run on load
window.addEventListener("DOMContentLoaded", resizeHeroWrapper);

// Debounced resize
window.addEventListener("resize", () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(resizeHeroWrapper, 300);
});

const circle = document.getElementById("cursorCircle");

let mouseX = 0,
    mouseY = 0;
let circleX = 0,
    circleY = 0;
const speed = 0.03;

document.addEventListener("mousemove", (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
});

// Smooth animation
function animate() {
    circleX += (mouseX - circleX) * speed;
    circleY += (mouseY - circleY) * speed;
    circle.style.left = `${circleX}px`;
    circle.style.top = `${circleY}px`;
    requestAnimationFrame(animate);
}
animate();

// Hover effect for buttons and links
document.addEventListener("mouseover", (e) => {
    const target = e.target;
    if (target.tagName === "A" || target.tagName === "BUTTON") {
        circle.classList.add("filled");
    }
});

document.addEventListener("mouseout", (e) => {
    const target = e.target;
    if (target.tagName === "A" || target.tagName === "BUTTON") {
        circle.classList.remove("filled");
    }
});

const swiper = new Swiper(".mySwiper", {
    slidesPerView: 3,
    spaceBetween: 20,
    autoplay: {
        delay: 3000, // Delay between slides (in ms)
        disableOnInteraction: false, // Continue autoplay after user interaction
    },
    loop: true,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 18,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
    },
});

function toggleAccordion(element) {
    const content = element.nextElementSibling;
    const icon = element.querySelector("svg");

    if (content.style.maxHeight === "0px" || content.style.maxHeight === "") {
        content.style.maxHeight = content.scrollHeight + "px";
        icon.classList.add("rotate-180");
    } else {
        content.style.maxHeight = "0px";
        icon.classList.remove("rotate-180");
    }
}

// Initialize the first item to be open
document.addEventListener("DOMContentLoaded", () => {
    const firstItemContent = document.querySelector(
        ".space-y-6 > div:first-child .mt-4"
    );
    const firstItemIcon = document.querySelector(
        ".space-y-6 > div:first-child svg"
    );
    if (firstItemContent) {
        firstItemContent.style.maxHeight = firstItemContent.scrollHeight + "px";
        firstItemIcon.classList.add("rotate-180");
    }
});

const hamburgerBtn = document.getElementById("hamburger-menu");
const mobileMenu = document.getElementById("mobile-menu");
const headerNav = document.getElementById("header-nav");
const headerBtn = document.getElementById("header-btn");

// Handle hamburger click
hamburgerBtn.addEventListener("click", () => {
    const isOpen = hamburgerBtn.classList.toggle("is-open");
    mobileMenu.classList.toggle("-translate-y-full");

    if (isOpen) {
        headerNav.classList.add("hidden");
        headerBtn.style.display = "none";
        document.body.classList.add("overflow-hidden");
    } else {
        if (window.innerWidth >= 1101) {
            headerNav.classList.remove("hidden");
            headerBtn.style.display = "inline-block";
        }
        document.body.classList.remove("overflow-hidden");
    }
});

// Close mobile menu on link click
mobileMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
        mobileMenu.classList.add("-translate-y-full");
        hamburgerBtn.classList.remove("is-open");
        if (window.innerWidth >= 1101) {
            headerNav.classList.remove("hidden");
            headerBtn.style.display = "inline-block";
        }
        document.body.classList.remove("overflow-hidden");
    });
});

// Handle resize
window.addEventListener("resize", () => {
    if (window.innerWidth >= 1101) {
        mobileMenu.classList.add("-translate-y-full");
        hamburgerBtn.classList.remove("is-open");
        headerNav.classList.remove("hidden");
        headerBtn.style.display = "inline-block";
        document.body.classList.remove("overflow-hidden");
    } else {
        headerNav.classList.add("hidden");
        headerBtn.style.display = "none";
    }
});

// Initial check on load
if (window.innerWidth < 1101) {
    headerNav.classList.add("hidden");
    headerBtn.style.display = "none";
} else {
    headerBtn.style.display = "inline-block";
}
