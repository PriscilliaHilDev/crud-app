<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link} from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { truncateByWords, formatDate } from '@/Common/utils';
import Paginator from "@/Components/Paginator.vue";
import OverlayDelete from "@/Components/OverlayDelete.vue";
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    myPosts: {
        type: Array,
    },
    pagination_links: {
        type: Array,
    }
});

const limiteCategories = ref(4);

function truncatedCategories(categories) {
            return categories.slice(0, 5);
}

// par default le modal n'est pas visivle
const isOverlayVisible = ref(false);

// stocker le post selectionné
const getPost = ref(null);

// afficher le model dynamiquement et recupérer le post que l'on souhaite supprimer
const toggleVisible = (post) => {

    // je stock le post dans getPost a chaque clique pour afficher le modeal
    getPost.value = post
    // si le modale s'affiche au click sur un bouton du modal il s'effacera
    isOverlayVisible.value = !isOverlayVisible.value;
}

const deletePost = (post) => {
    isOverlayVisible.value = false;
    router.post(route('post.destroy', { post:post}));
}


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template v-slot:header>
            <h2 class="font-semibold text-xl">Dashboard</h2>
        </template>
        <div v-if="myPosts.length > 0">
            <OverlayDelete :currentItem="getPost" :show="isOverlayVisible" :toggleVisible="toggleVisible" textDial="Souhaitez vous supprimer ce commentaire ?" :funcDelete="deletePost"/>
            <div class="mx-auto max-w-7xl px-6 lg:px-8 xl:grid xl:grid-cols-2 xl:gap-x-8 xl:gap-y-20 py-24 pt-14">
                <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                    <li v-for="postItem of myPosts" :key="postItem.post.id" class="bg-slate-100 p-6 rounded-lg shadow-md">
                        <div class="flex justify-between mb-4">
                            <Link class="-m-1.5 p-1.5" :href="route('post.edit', { post: postItem.post })">
                                <font-awesome-icon icon="edit" size="2x" color="#B9255F" />
                            </Link>
                            <button class="-m-1.5 p-1.5" @click="toggleVisible(postItem.post)" >
                                <font-awesome-icon icon="close" size="2x" color="#B9255F" />
                            </button>
                        </div>
                        <img class="w-full h-48 object-cover rounded-t-lg"  :src="postItem.image ? '/storage' + postItem.image : '/storage/images/default-image.jpg'" alt="Post image" />
                        <div class="p-4">
                            <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">{{ postItem.post.title }}</h3>
                            <p class="text-sm leading-6 text-gray-700 text-justify">{{ truncateByWords(postItem.post.content, 18) }}</p>
                            <div class="mt-4">
                                <span v-for="category in truncatedCategories(postItem.categories)" :key="category.id" class="inline-block bg-pink-200 text-pink-900 rounded-full px-3 py-1 text-sm font-semibold mr-2">
                                    {{ category.name }}
                                </span>
                                <span v-if="postItem.categories.length > limiteCategories" class="inline-block text-gray-700 px-3 py-1 text-sm font-semibold mr-2">
                                    ...
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <Link class="-m-1.5 p-1.5 font-bold text-pink-800 underline" :href="route('post.show', { post: postItem.post })">
                                    Lire plus 
                                </Link>
                                <div class="text-sm text-gray-800">
                                    <p>Publié le : {{ new Date(postItem.post.updated_at).toLocaleDateString() }}</p>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <Paginator :pagination="pagination_links" />
        </div>

        <div v-else class="max-w-2xl text-center py-20">
            <p class="text-lg leading-8 text-gray-600">Vous n'avez encore écrit aucun article.</p>
        </div>
    </AuthenticatedLayout>
</template>
