<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome to SupportHub AI" />

    <div class="bg-gray-50 text-slate-800 min-h-screen">
        <div class="relative flex flex-col items-center">
            <div class="container-pro py-20">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                       <!-- You can put your logo here -->
                       <svg class="h-12 w-auto text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.375a6.375 6.375 0 006.375-6.375h-12.75A6.375 6.375 0 0012 18.375zM12 5.625A6.375 6.375 0 0118.375 12h-12.75A6.375 6.375 0 0112 5.625z" />
                       </svg>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                        Dashboard
                        </Link>

                        <template v-else>
                            <Link :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                            Log in
                            </Link>

                            <Link v-if="canRegister" :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                            Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main>
                    <div class="text-center max-w-3xl mx-auto">
                        <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
                            The Smartest Way to Manage Customer Support
                        </h1>
                        <p class="mt-4 text-lg text-slate-600">
                            SupportHub AI helps agents focus on what matters. Triage, summarize, and automate repetitive tasks with a simple interface.
                        </p>

                        <div class="mt-8 flex items-center justify-center gap-4">
                            <Link :href="route('register')">
                                <PrimaryButton>Get started</PrimaryButton>
                            </Link>

                            <Link href="#" class="text-sm font-semibold text-slate-700 hover:text-primary-600">Learn more →</Link>
                        </div>
                    </div>

                    <section class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="card p-6">
                            <h3 class="font-semibold text-lg">Smart Triage</h3>
                            <p class="mt-2 text-sm text-slate-600">Automatically prioritize tickets so your team handles urgent issues first.</p>
                        </div>

                        <div class="card p-6">
                            <h3 class="font-semibold text-lg">Agent Assist</h3>
                            <p class="mt-2 text-sm text-slate-600">Summaries and suggested replies to speed up resolutions.</p>
                        </div>

                        <div class="card p-6">
                            <h3 class="font-semibold text-lg">Easy Collaboration</h3>
                            <p class="mt-2 text-sm text-slate-600">Assign, comment, and manage tickets with a clear workflow.</p>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</template>