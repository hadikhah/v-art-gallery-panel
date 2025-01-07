<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import GithubIcon from "@/Components/Icons/GithubIcon.vue";
import GoogleIcon from "@/Components/Icons/GoogleIcon.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const page = usePage();
const locale = computed(() => page.props.locale);

const form = useForm({
    email: "test@example.com",
    password: "password",
    remember: false,
});

const submit = () => {
    form.post(route("login", { lang: page.props.locale }), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-white border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 block">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request', { lang: locale })"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-300"
                >
                    Forgot your password?
                </Link>
            </div>
            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('register', { lang: locale })"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-300"
                >
                    Don't have an account yet? (create one)
                </Link>
                <PrimaryButton
                    type="submit"
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>

            <!-- GitHub Login Button -->
            <div class="my-5">
                <div class="mt-10">
                    <a :href="route('login.github')" class="flex items-center">
                        <PrimaryButton
                            type="button"
                            class="w-full justify-center tracking-widest"
                        >
                            <GithubIcon />
                            Log in with GitHub
                        </PrimaryButton>
                    </a>
                </div>

                <!-- Google Login Button -->
                <div class="mt-4">
                    <a :href="route('login.google')" class="flex items-center">
                        <SecondaryButton
                            class="w-full justify-center tracking-widest"
                        >
                            <GoogleIcon />
                            Log in with Google
                        </SecondaryButton>
                    </a>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
