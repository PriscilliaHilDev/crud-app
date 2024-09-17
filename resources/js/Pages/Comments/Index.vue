
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link} from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    // myPosts: {
    //     type: Array,
    // },
    // pagination_links: {
    //     type: Array,
    // }
});

onMounted(() => {
    // console.log('props', props.myPosts)
});


</script>

<template>
    <!-- <Head title="Dashboard" /> -->

    <AuthenticatedLayout>
        <template v-slot:header>
        </template>

        <div v-if="myPosts.length > 0">
            <div class="max-w-4xl mx-auto">
                <!-- Article Details Card -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 bg-gray-100">
                        <h2 class="text-xl font-semibold text-gray-800">Titre de l'article</h2>
                        <p class="text-sm text-gray-600">Date de publication : 14 juin 2024</p>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <img src="https://via.placeholder.com/600x400" alt="Article Image" class="rounded-lg mb-4">
                        <p class="text-gray-700 leading-relaxed">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat.
                        </p>
                    </div>

                    <!-- Comment Form -->
                    <form class="px-6 py-4 bg-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Ajouter un commentaire</h3>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input id="name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-400 focus:ring focus:ring-pink-400 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700">Commentaire</label>
                            <textarea id="comment" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-400 focus:ring focus:ring-pink-400 focus:ring-opacity-50"></textarea>
                        </div>
                        <button type="submit" class="inline-block bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600 focus:outline-none focus:bg-pink-600">Poster le commentaire</button>
                    </form>

                    <!-- List of Comments -->
                    <div class="p-6 bg-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Commentaires</h3>
                        <!-- Example comment -->
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-700">Par <span class="font-semibold">Utilisateur</span> le 14 juin 2024</p>
                            <p class="text-gray-800 leading-relaxed mt-2">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat.
                            </p>
                        </div>
                        <!-- Repeat for other comments -->
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="max-w-2xl text-center py-20">
            <p class="text-lg leading-8 text-gray-600">Vous n'avez encore écrit aucun article.</p>
        </div>
    </AuthenticatedLayout>
</template>
