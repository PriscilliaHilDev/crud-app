<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, computed} from 'vue';
import { formatDate } from '@/Common/utils';
import { useForm } from '@inertiajs/vue3';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import OverlayDelete from '@/Components/OverlayDelete.vue';



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

// contenu du formulaire
const form = useForm({
    content: "",
});

// par defaut les bouton pour soumettre le formulaire en cas de modification ne s'afficheront pas, mais le bouton de creation de commentaire oui
const editMode = ref(false);

// par default le modal n'est pas visivle
const isOverlayVisible = ref(false);

// stocker le commentaire selectionné pour la suppression
const getDeleteComment = ref(null);
// stocker le commentaire selectionné pour la modification
const getEditComment = ref(null);

// afficher le model dynamiquement et transmettre le comment que l'on souhaite supprimer
const toggleVisible = (comment) => {

    // je stock le commentaire dans getDeleteComment depuis le bouton supprimer dans le dropdown associé a chaque commentaire
    getDeleteComment.value = comment;
    // afficher le modale qui est initialement caché (isOberlayVisible false) quand je clique sur supprimer depuis le dropdown associé a un commentaire
    isOverlayVisible.value = !isOverlayVisible.value;
}

// desactive le bouton poster un commentaire si le textarea est vide
// activer le bouton poster un commentaire quand le textarea n'est pas vide
const isDisabled = computed(() => {
    return form.content.trim() === '';
});


// afficher le bouton de modification de commentaire
// passer a l'event submit du form la fonction editcomment
// remplir le textarea par le contenu du commentaire deja existant
const getEditMode = (comment) =>{
    editMode.value = true;
    form.content = comment.content;
    getEditComment.value = comment;
}

// soumettre la mise a jour du commentaire a travers le formulaire
const editComment = () => {
    form.patch(route('comment.update', {comment:getEditComment.value}), {
        preserveScroll: true,
        onSuccess: () => {
            form.content = '';
            editMode.value = false; // Réinitialise le contenu du textarea après succès
        }, onError: (e) => {
            console.log('y pas bon erreurrr', e)
        }
    });
    // en cas de succes dans la soumission du form mettre editmode a false !!!!!!!
}

// soumettre la suppression du commentaire
const deleteComment = () => {
    form.delete(route('comment.destroy', {comment:getDeleteComment.value}), {
        preserveScroll: true,
        onSuccess: () => {
            //cache le modal en cas de succes de la suppression
            isOverlayVisible.value = false;

        }, onError: (e) => {
            console.log('error', e)
            //cache le modal meme en cas d'echec de la suppression
            isOverlayVisible.value = false;

        }
    });
}

// annuler la mise a jour du commentaire
const cancelComment = () =>{
    editMode.value = false;
    form.content =''
}



// switcher entre la fonction creation de commentaire et la fonction edition de commentaire en fonction de editMode activé si l'utilisation clique sur le bouton modifier commentaire
const toggleEditComment = () => {
    if(editMode.value){
        console.log('yo')
        return editComment()
    }

    if(!editMode.value){
        console.log('he')
        return createComment()
    }
}

// creation d'un nouveau commentaire
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

// au montage du composant
onMounted(() => {
  console.log(props.comments)
});


</script>

<template>
    <Head title="Accueil" />

    <AuthenticatedLayout>
       <template #header>
            <h2 class="font-semibold text-xl "></h2>
        </template>
        <OverlayDelete :currentItem="getDeleteComment" :show="isOverlayVisible" :toggleVisible="toggleVisible" textDial="Souhaitez vous supprimer ce commentaire ?" :funcDelete="deleteComment"/>
        <div class="flex items-center justify-center py-32">
            <div class="max-w-7xl  mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 w-2/3">
                <!-- Première carte - Détail de l'article -->
                <div class="col-span-full relative md:col-span-2 bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-100">
                        <h2 class="text-xl font-semibold text-pink-900">{{ post.post.title }}</h2>
                        <p class="text-sm text-pink-900">Publié le {{ formatDate(post.post.created_at) }}</p>
                    </div>
                    <div class="p-6 flex items-center justify-center "> <!-- Ajout des classes pour centrer -->
                        <img :src="post.image ? '/storage/' + post.image : '/images/default-image.jpg'" alt="Article Image" class="w-[400px] rounded-lg mb-4">
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 text-justify leading-relaxed mb-8 ">
                        {{ post.post.content }}
                        </p>
                        <div class="p-8 absolute bottom-0 left-0 right-0">
                            <p class="text-xs text-right text-gray-400 mt-4">Par <span class="font-semibold">{{ post.author.name }}</span></p>
                        </div>
                    </div>
                </div>

              <!-- Deuxième carte - Commentaires -->
                <div class="col-span-full relative md:col-span-1 w-full min-h-[500px] bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-100">
                        <h2 class="text-xl font-semibold text-pink-900">Commentaires</h2>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[300px]">
                        <!-- Vérifier s'il y a des commentaires -->
                        <div v-if="comments.length === 0" class="text-gray-700">
                            Il n'y a pas encore de commentaires pour cet article.
                        </div>
                        <!-- Liste des commentaires -->
                        <div v-else>
                            <div v-for="comment in comments" :key="comment.id" class="border-t border-gray-200 pt-4 relative">
                                <!-- Informations sur le commentaire -->
                                <p class="text-sm text-pink-700">
                                    Par <span class="font-semibold">{{ comment.user.name }}</span> le {{ formatDate(comment.created_at) }}
                                </p>
                                <p class="text-gray-800 leading-relaxed mt-2">{{ comment.content }}</p>

                                <!-- Dropdown pour l'utilisateur authentifié -->
                                <div v-if="comment.user.id === userID">
                                    <Menu as="div" class="absolute top-0 right-0 mt-2 mr-2">
                                        <!-- Bouton pour ouvrir le menu -->
                                        <MenuButton class="inline-flex items-center px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                            <font-awesome-icon :icon="['fas', 'ellipsis-vertical']" />
                                        </MenuButton>
                                        <!-- Contenu du menu -->
                                        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                            <MenuItems class="absolute right-0 z-10 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                <MenuItem v-slot="{ active }">
                                                    <a href="#" @click="getEditMode(comment)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Modifier</a>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <a href="#" @click="toggleVisible(comment)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Supprimer</a>
                                                </MenuItem>
                                            </MenuItems>
                                        </transition>
                                    </Menu>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Formulaire d'ajout de commentaire -->
                    <div class="px-6 py-4 bg-gray-100  absolute bottom-0 left-0 right-0 ">
                        <h3 class="text-lg font-semibold text-pink-900 mb-2">Ajouter un commentaire</h3>
                        <form @submit.prevent="toggleEditComment" >
                            <div class="mb-4">
                                <textarea id="comment" name="content" v-model="form.content" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-400 focus:ring focus:ring-pink-400 focus:ring-opacity-50"></textarea>
                                <InputError :message="form.errors.content" />
                            </div>
                            <div v-if='editMode' class="flex space-x-2">
                                <button type="submit" class="inline-block bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600 focus:outline-none focus:bg-pink-600">Modifier</button>
                                <button type="button" @click="cancelComment" class="inline-block bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Annuler</button>
                            </div>
                            <button v-else :disabled="isDisabled" type="submit" class="inline-block disabled:opacity-50 bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600 focus:outline-none focus:bg-pink-600">Poster le commentaire</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>