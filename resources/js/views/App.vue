<template>
    <!-- eslint-disable -->
    <div id="app">
        <div class="min-h-screen bg-gray-200">
            <nav class="bg-brand-500 border-t-4 border-brand-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <router-link to="/" class="text-2xl text-white font-bold">
                                    CraftBox
                                </router-link>
                            </div>
                            <div class="hidden md:block">
                                <div class="ml-10 flex items-baseline">
                                    <router-link to="/" exact-active-class="text-white bg-brand-600" class="px-3 py-2 rounded-md text-sm font-medium text-brand-100 hover:text-white hover:bg-brand-700 focus:outline-none focus:text-white focus:bg-brand-700">Dashboard</router-link>
                                    <router-link to="/servers" active-class="text-white bg-brand-600"  class="ml-4 px-3 py-2 rounded-md text-sm font-medium text-brand-100 hover:text-white hover:bg-brand-700 focus:outline-none focus:text-white focus:bg-brand-700">Servers</router-link>
                                </div>
                            </div>
                        </div>
                        <div class="-mr-2 flex md:hidden">
                            <!-- Mobile menu button -->
                            <button @click="menuOpen = !menuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-brand-400 hover:text-white hover:bg-brand-700 focus:outline-none focus:bg-brand-700 focus:text-white">
                                <svg :class="[menuOpen ? 'hidden' : 'block']" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg :class="[menuOpen ? 'block' : 'hidden']" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="[menuOpen ? 'block' : 'hidden']" class="md:hidden">
                    <div class="px-2 pt-2 pb-3 sm:px-3">
                        <router-link to="/" exact-active-class="text-white bg-brand-900" class="block px-3 py-2 rounded-md text-base font-medium text-brand-300 hover:text-white hover:bg-brand-700 focus:outline-none focus:text-white focus:bg-brand-700">Server</router-link>
                        <router-link to="/servers" exact-active-class="text-white bg-brand-900" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-brand-300 hover:text-white hover:bg-brand-700 focus:outline-none focus:text-white focus:bg-brand-700">Site</router-link>
                    </div>
                </div>
            </nav>

            <div class="py-10">
                <router-view/>
            </div>
        </div>

        <portal-target name="modals"></portal-target>
    </div>
</template>

<script>
    export default {
        data: () => ({
            menuOpen: false,
        }),
    };
</script>
