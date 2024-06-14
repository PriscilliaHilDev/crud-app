<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { formatDate } from '@/Common/utils';
import { Link, useForm, usePage } from '@inertiajs/vue3';



const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    comments : {
        type: Object,
        required: true
    },
    userID: {
        type: Number,
        required: true,
        // validator: (value) => Number.isInteger(value)
    }
});

const form = useForm({
    content: "",
});

const createComment = () => {
    form.put(route('comment.store', {post:props.post.post}), {
        preserveScroll: true,
        onSuccess: () => {
            form.content = ''; // Réinitialise le contenu du textarea après succès
        }, onError: (e) => {
            console.log('error', e)
        }
    });
};

const toggleDropdown = (comment) => {
    comment.dropdownOpen = !comment.dropdownOpen;
};


const editComment = (comment) => {
    // Logique pour modifier le commentaire
    console.log('Modifier le commentaire :', comment);
};

const deleteComment = (comment) => {
    // Logique pour supprimer le commentaire
    console.log('Supprimer le commentaire :', comment);
};

    onMounted(() => {
        console.log(props.comments[0].author.id, props.userID )
        // Initialiser les états dropdownOpen pour chaque commentaire
        props.comments.forEach(comment => {
            comment.dropdownOpen = false;
        });
    });
</script>

<template>
    <Head title="Accueil" />

    <AuthenticatedLayout>
       <template #header>
            <h2 class="font-semibold text-xl "></h2>
        </template>
        <div class="flex items-center justify-center py-32">
            <div class="max-w-7xl  mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Première carte - Détail de l'article -->
                <div class="col-span-full md:col-span-2 bg-white shadow-md rounded-lg overflow-hidden">
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
                <div class="col-span-full relative md:col-span-1 bg-white shadow-md rounded-lg overflow-hidden">
                    <div>
                        <div class="px-6 py-4 bg-gray-100">
                            <h2 class="text-xl font-semibold text-gray-800">Commentaires</h2>
                        </div>
                        <div class="p-6 overflow-y-auto max-h-[500px]">
                            <!-- Vérifier s'il y a des commentaires -->
                            <div v-if="comments.length === 0" class="text-gray-700">
                                Il n'y a pas encore de commentaires pour cet article.
                            </div>
                            <!-- Liste des commentaires -->
                            <div v-else>
                                <div v-for="comment in comments" :key="comment.comment.id" class="border-t border-gray-200 pt-4">
                                    <p class="text-sm text-pink-700">Par <span class="font-semibold">{{ comment.author.name }}</span> le {{ formatDate(comment.comment.created_at) }}</p>
                                    <p class="text-gray-800 leading-relaxed mt-2">{{ comment.comment.content }}</p>
                                    
                                    <!-- Dropdown pour l'utilisateur authentifié -->
                                    <div v-if="comment.author.id === userID" class="mt-2 relative sm:mt-0 sm:ml-2">
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Formulaire d'ajout de commentaire -->
                    <form @submit.prevent="createComment" class="absolute bottom-0 left-0 right-0 px-6 py-4 bg-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Ajouter un commentaire</h3>
                    
                        <div class="mb-4">
                            <textarea id="comment" name="content" v-model="form.content" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-400 focus:ring focus:ring-pink-400 focus:ring-opacity-50"></textarea>
                            <InputError :message="form.errors.content" />
                        </div>
                        <button type="submit" class="inline-block bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600 focus:outline-none focus:bg-pink-600">Poster le commentaire</button>
                    </form>
                </div>
            </div>
        </div>

    
    </AuthenticatedLayout>
</template>