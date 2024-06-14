<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { formatDate } from '@/Common/utils';

const props = defineProps({
  post: {
        type: Object,
        required: true
    },
});


onMounted(() => console.log('article', props.post));

</script>

<template>
    <Head title="Accueil" />

    <AuthenticatedLayout>
       <template #header>
            <h2 class="font-semibold text-xl "></h2>
        </template>
        <div class="flex items-center justify-center py-32">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Première carte - Détail de l'article -->
                <div class="col-span-2 bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-100">
                        <h2 class="text-xl font-semibold text-gray-800">{{ post.post.title }}</h2>
                        <p class="text-sm text-gray-600">Publié le {{ formatDate(post.post.created_at) }}</p>
                    </div>
                    <div class="p-6 flex items-center justify-center"> <!-- Ajout des classes pour centrer -->
                        <img :src="'/storage/' + post.image"  alt="Article Image" class="rounded-lg mb-4">
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 text-justify leading-relaxed">
                           {{ post.post.content }}
                        </p>
                        <p class="text-xs text-right text-gray-400 mt-4">Par <span class="font-semibold">{{ post.author.name }}</span></p>
                    </div>
                </div>

                <!-- Deuxième carte - Commentaires -->
                <div class="col-span-1 bg-white shadow-md rounded-lg overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="px-6 py-4 bg-gray-100">
                            <h2 class="text-xl font-semibold text-gray-800">Commentaires</h2>
                        </div>
                        <div class="p-6 overflow-y-auto max-h-96">
                            <!-- Liste des commentaires -->
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm text-gray-700">Par <span class="font-semibold">Utilisateur</span> le 14 juin 2024</p>
                                <p class="text-gray-800 leading-relaxed mt-2">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis lorem ut libero malesuada feugiat.
                                </p>
                            </div>
                            <!-- Répétez pour les autres commentaires -->
                        </div>
                    </div>
                
                    <!-- Formulaire d'ajout de commentaire -->
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
                </div>
            </div>
        </div>







    
    </AuthenticatedLayout>
</template>