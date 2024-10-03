<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import PushNotification from '@/Components/PushNotification.vue'; // Composant pour les notifications push
import NotificationPopoverMobile from '@/Components/NotificationPopoverMobile.vue';
import NotificationPopover from '@/Components/NotificationPopover.vue';

// Importation des données de la page via Inertia.js
const page = usePage();

// Définition des props
const props = defineProps({
  routeName: {
    type: String,
  },
});

// Variables réactives
const showingNavigationDropdown = ref(false); // Pour afficher/cacher le menu de navigation
const notificationManager = ref(null); // Référence au composant de gestion des notifications
const postToPush = ref(null); // Contient les informations sur le post lié à la notification
const popoverManager = ref(null); // Référence pour le popover des notifications

const isAdmin = computed(() => {
  return page.props.admin === true; // Check if admin is true
});

// Fonction déclenchée au montage du composant
onMounted(() => {
    // Récupérer l'ID de l'utilisateur connecté depuis les props de la page
    const userId = page.props.auth.user.id;

    // Écoute des événements en temps réel grâce à Laravel Echo si un utilisateur est connecté
    if (window.Echo && userId) {
        // Canal privé pour écouter les événements spécifiques à l'utilisateur
        window.Echo.private(`user.${userId}`)
            .listen('.comment-added', (e) => {
                if(notificationManager.value){
                    // Stocker les informations du post lié à la notification
                    postToPush.value = e.comment.post;
                    // Afficher la notification via la méthode `showPush` du composant PushNotification
                    notificationManager.value.showPush(e.comment.author, e.comment.message, e.comment.createdAt);
                }
                
                // Mettre à jour le popover des notifications
                if (popoverManager.value) {
                    popoverManager.value.fetchNotifications();
                }
            });
    }

    // Vérifier les messages flash stockés dans la session pour les afficher
    const successMessage = page.props.flash.message;
    if (successMessage && notificationManager.value) {
        notificationManager.value.showToast(successMessage); // Afficher le message flash via Toast
    }

    // Charger les notifications pour l'utilisateur
    popoverManager.value.fetchNotifications();
});
</script>


<template>
    <div>
        <div class="relative min-h-screen bg-pink-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('post.list')">
                                    <font-awesome-icon icon="heart" size="2x" color="#B9255F" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('post.create')" :active="route().current('post.create')">
                                    Ecrire un article
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('post.user')" :active="route().current('post.user')">
                                    Mes articles
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('search')" :active="route().current('search')">
                                    Recherche
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NotificationPopover ref="popoverManager" />
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink  v-if="isAdmin" href="/admin" > ADMINISTRATION </DropdownLink>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('post.create')" :active="route().current('post.create')">
                            Ecrire un article
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('post.user')" :active="route().current('post.user')">
                            Mes articles
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('search')" :active="route().current('search')">
                            Recherche
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-2 p-4 space-y-1">
                        <NotificationPopoverMobile ref="popoverManager"/>
                    </div>
                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink v-if="isAdmin" href="/admin">ADMINISTRATION</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>

                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <PushNotification ref="notificationManager" :post="postToPush" /> <!-- Utiliser le composant ici -->
                <slot />
            </main>
        </div>
    </div>
</template>