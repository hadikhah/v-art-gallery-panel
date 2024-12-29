<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import CloudArrowUpIcon from "@/Components/Icons/CloudArrowUpIcon.vue";
import CursorArrowRaysIcon from "@/Components/Icons/CursorArrowRaysIcon.vue";
import ShareIcon from "@/Components/Icons/ShareIcon.vue";
import ViewGalleryIcon from "@/Components/Icons/ViewGalleryIcon.vue";
import Snackbar from "@/Components/Snackbar.vue";
import ThemeToggleButton from "@/Components/ThemeToggleButton.vue";
import { useToastStore } from "@/Store/toast";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const locale = page.props.locale;

const user = page.props.auth.user;

const features = [
    {
        name: "Virtual Exhibition Space",
        description:
            "Create your own virtual gallery space and customize it to match your artistic vision.",
        icon: ViewGalleryIcon,
    },
    {
        name: "Easy Upload",
        description:
            "Simply upload your artwork and we'll handle the rest, creating a beautiful 3D display.",
        icon: CloudArrowUpIcon,
    },
    {
        name: "Share Worldwide",
        description:
            "Share your virtual gallery with anyone around the world with a simple link.",
        icon: ShareIcon,
    },
    {
        name: "Interactive Experience",
        description:
            "Visitors can walk through your gallery and interact with your artwork in real-time.",
        icon: CursorArrowRaysIcon,
    },
];

// Example of using the toast store
const toast = useToastStore();

// You can trigger toasts like this:
toast.addToast(page.props.message.success, "success");
toast.addToast(page.props.message.error, "error");
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <Snackbar />

        <!-- Hero Section -->
        <div class="relative bg-gray-900 dark:bg-gray-800 overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div
                    class="relative z-10 pb-8 bg-gray-900 dark:bg-gray-800 sm:pb-16 md:pb-20 lg:w-full lg:pb-28 xl:pb-32"
                >
                    <div class="pt-6 px-4 sm:px-6 lg:px-8">
                        <!-- Navigation -->
                        <nav class="flex items-center justify-between sm:h-10">
                            <div
                                class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0"
                            >
                                <div
                                    class="flex items-center justify-normal sm:justify-between w-full sm:w-auto"
                                >
                                    <ApplicationLogo
                                        class="block sm:h-12 w-auto fill-current text-gray-800 dark:text-gray-200"
                                    />
                                    <a
                                        href="#"
                                        class="text-2xl sm:font-bold text-white"
                                        >Virtual Gallery</a
                                    >
                                </div>
                            </div>
                            <div class="flex md:ml-10 md:pr-4 md:space-x-8">
                                <ThemeToggleButton />

                                <Link
                                    v-if="!user"
                                    :href="route('login', { lang: locale })"
                                    class="font-medium text-gray-200 hover:text-white"
                                >
                                    Login
                                </Link>
                                <Link
                                    v-if="user"
                                    :href="route('dashboard', { lang: locale })"
                                    class="font-medium text-gray-200 hover:text-white"
                                >
                                    Dashboard
                                </Link>
                            </div>
                        </nav>
                    </div>

                    <main
                        class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28"
                    >
                        <div class="sm:text-center lg:text-left">
                            <h1
                                class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl"
                            >
                                <span class="block">Showcase Your Art in</span>
                                <span class="block text-indigo-400"
                                    >Virtual Reality</span
                                >
                            </h1>
                            <p
                                class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0"
                            >
                                Upload your artwork and experience it in an
                                immersive virtual gallery. Share your creations
                                with the world in a whole new way.
                            </p>
                            <div
                                class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
                            >
                                <div class="rounded-md shadow">
                                    <Link
                                        :href="
                                            route(
                                                !user
                                                    ? 'register'
                                                    : 'dashboard',
                                                { lang: locale }
                                            )
                                        "
                                    >
                                        <button
                                            class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10"
                                        >
                                            Get Started
                                        </button>
                                    </Link>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a
                                        href="#features"
                                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-indigo-400 dark:hover:bg-gray-700 md:py-4 md:text-lg md:px-10"
                                    >
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-12 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2
                        class="text-base text-indigo-600 font-semibold tracking-wide uppercase"
                    >
                        Features
                    </h2>
                    <p
                        class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-gray-200 sm:text-4xl"
                    >
                        A better way to showcase art
                    </p>
                </div>

                <div class="mt-10">
                    <div
                        class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10"
                    >
                        <!-- Feature items -->
                        <div
                            v-for="feature in features"
                            :key="feature.name"
                            class="relative"
                        >
                            <div
                                class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white"
                            >
                                <component
                                    :is="feature.icon"
                                    class="h-6 w-6"
                                    aria-hidden="true"
                                />
                            </div>
                            <p
                                class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-gray-200"
                            >
                                {{ feature.name }}
                            </p>
                            <p
                                class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400"
                            >
                                {{ feature.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            About Us
                        </h3>
                        <p class="mt-4 text-gray-400 dark:text-gray-300">
                            Virtual Gallery is a platform for artists to
                            showcase their work in an immersive virtual
                            environment. Join us and share your art with the
                            world.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            Quick Links
                        </h3>
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a
                                    href="#"
                                    class="text-gray-400 hover:text-white dark:hover:text-indigo-400"
                                >
                                    Home
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-gray-400 hover:text-white dark:hover:text-indigo-400"
                                >
                                    Features
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-gray-400 hover:text-white dark:hover:text-indigo-400"
                                >
                                    Pricing
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-gray-400 hover:text-white dark:hover:text-indigo-400"
                                >
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Social Media Links -->
                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            Follow Us
                        </h3>
                        <div class="mt-4 flex space-x-4">
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white dark:hover:text-indigo-400"
                            >
                                <span class="sr-only">Facebook</span>
                                <svg
                                    class="h-6 w-6"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white dark:hover:text-indigo-400"
                            >
                                <span class="sr-only">Twitter</span>
                                <svg
                                    class="h-6 w-6"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"
                                    />
                                </svg>
                            </a>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white dark:hover:text-indigo-400"
                            >
                                <span class="sr-only">Instagram</span>
                                <svg
                                    class="h-6 w-6"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0 2.163c-3.259 0-3.667.01-4.947.058-2.905.133-4.346 1.707-4.479 4.58-.046 1.281-.057 1.689-.057 4.948 0 3.259.01 3.668.058 4.948.133 2.904 1.707 4.346 4.58 4.479 1.281.046 1.689.057 4.948.057 3.259 0 3.668-.01 4.948-.058 2.904-.133 4.346-1.707 4.479-4.58.046-1.281.057-1.689.057-4.948 0-3.259-.01-3.668-.058-4.948-.133-2.904-1.707-4.346-4.58-4.479-1.281-.046-1.689-.057-4.948-.057zm0 5.837c-1.811 0-3.289 1.478-3.289 3.29 0 1.811 1.478 3.289 3.289 3.289 1.811 0 3.289-1.478 3.289-3.289 0-1.811-1.478-3.29-3.289-3.29zm0 5.837c-1.405 0-2.55-1.145-2.55-2.55 0-1.405 1.145-2.55 2.55-2.55 1.405 0 2.55 1.145 2.55 2.55 0 1.405-1.145 2.55-2.55 2.55zm6.409-7.028c0 .796-.645 1.44-1.44 1.44-.796 0-1.44-.645-1.44-1.44 0-.796.645-1.44 1.44-1.44.796 0 1.44.645 1.44 1.44z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mt-8 border-t border-gray-800 pt-8 text-center">
                    <p class="text-base text-gray-400 dark:text-gray-300">
                        &copy; {{ new Date().getFullYear() }} Virtual Gallery.
                        All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
