<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const navigation = [
  { name: 'Connexion', current: true },
  { name: 'Inscription', current: true },
]

onMounted(() => console.log('props', $page));

</script>

<template >
    <Head title="Welcome" />
    <div class="h-full bg-pink-100">
        <header>
            <div class="container mx-auto ">
                <Disclosure as="nav" v-slot="{ open }">
                    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                        <div class="relative flex h-16 items-center justify-between">
                            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                            <!-- Mobile menu button-->
                            <DisclosureButton class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-pink-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                <span class="absolute -inset-0.5" />
                                <span class="sr-only">Open main menu</span>
                                <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                                <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                            </DisclosureButton>
                            </div>
                            <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                                <a v-if="$page.props.auth.user" :href="route('dashboard')">
                                    <font-awesome-icon icon="heart" size="3x" color="#B9255F" />
                                </a>
                                <a v-if="!$page.props.auth.user" :href="route">
                                    <font-awesome-icon icon="heart" size="3x" color="#B9255F" />
                                </a>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                                <div class="hidden sm:ml-6 sm:block">
                                    <div class="flex space-x-4">
                                        <a v-for="item in navigation" :key="item.name" :href="item.name === 'Connexion' ? route('login') : (item.name === 'Inscription' ? route('register') : '#')" :class="[item.current ? 'bg-pink-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <DisclosurePanel class="sm:hidden">
                        <div class="space-y-1 px-2 pb-3 pt-2">
                            <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.name === 'Connexion' ? route('login') : (item.name === 'Inscription' ? route('register') : '#')"  :class="[item.current ?  'text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
                        </div>
                    </DisclosurePanel>
                </Disclosure>      
            </div>
        </header>
        <main class="container mx-auto">
            <div class="mx-auto max-w-7xl sm:px-6 sm:py-32 lg:px-8">
            <div class="relative bg-pink-900 px-6 lg:flex lg:rounded-2xl h-[700px]">
                <div class="mx-auto max-w-md lg:mx-5 flex items-center lg:py-40 lg:text-left">
                    <h2 class="font-bold tracking-tight text-white lg:text-5xl text-3xl lg:py-10 pt-10 text-center">Ecrivez et partagez vos plus belles pensées avec nous. </h2>
                </div>
                <div class="relative flex item-center lg:justify-center py-10">
                    <img class=" rounded-full" src="/storage/images/home-blog.jpg" alt="App screenshot" />
                </div>
            </div>
            </div>
        </main>
        <footer class="py-16 text-center ">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
        </footer>
    </div>
   
</template>
