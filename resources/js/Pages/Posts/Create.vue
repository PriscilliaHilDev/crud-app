<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref, watch } from 'vue';
import MultiSelect from 'primevue/multiselect';
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
  posts: {
        type: Array,
    },
    pagination_links: {
        type: Array,
    },
    categories_list: {
        type: Array,
    },
});

// const agreed = ref(false);

const previewImage = ref(null);

const handleFileChange = (event) => {
    // Récupère le fichier sélectionné par l'utilisateur
    const file = event.target.files[0];
    
    // Vérifie si un fichier a été sélectionné
    if (file) {
        // Crée un nouvel objet FileReader pour lire le contenu du fichier
        const reader = new FileReader();
        
        // lorsque le fichier a été lu avec succès
        reader.onload = (e) => {
            // Met à jour la prévisualisation de l'image 
            previewImage.value = e.target.result;
        };

        // Lit le contenu du fichier comme une URL de données base64
        reader.readAsDataURL(file);
        
        // Assigne le fichier à l'état du formulaire
        form.image = file;

    } else {
        // Si aucun fichier n'a été sélectionné, réinitialise la prévisualisation et le champ du formulaire
        previewImage.value = null;
        form.image = null;
    }
};


   // Watcher pour mettre à jour form.categories lorsque selectedOptions change
   watch(props.categories_list, (newSelectedOptions) => {
      form.categories = newSelectedOptions;
    });


const form = useForm({
    title: "",
    content: "",
    image:null,
    categories:[]
});


const createPost = () => {
    form.post(route('post.store'), {
        preserveScroll: true,
        onSuccess: () => {
            console.log(form)
        }, onError: (e) => {
            console.log('error', e)
        }
    });
};

onMounted(() => console.log('cat', props.categories_list));

</script>


<template>
    <AuthenticatedLayout>
       <template #header>
            <!-- <h2 class="font-semibold text-xl">Creation d'un nouvel article </h2> -->
        </template>
        <div class="mx-auto flex justify-center bg-slate-50 mb-10">
            <div class="isolate px-6 py-24 sm:py-32 lg:px-8">
                <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]" aria-hidden="true" style="pointer-events: none;">
                    <div class="relative left-1/2 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Creation d'un nouvel article </h2>
                    <p class="mt-2 text-lg leading-8 text-gray-600">Laissez libre cours à votre imagination</p>
                </div>
                <form @submit.prevent="createPost"  class="mx-auto mt-16 max-w-xl sm:mt-20">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                        <!-- <div>
                            <label for="first-name" class="block text-sm font-semibold leading-6 text-gray-900">First name</label>
                            <div class="mt-2.5">
                                <input type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div> -->
                        <!-- <div>
                            <label for="last-name" class="block text-sm font-semibold leading-6 text-gray-900">Last name</label>
                            <div class="mt-2.5">
                                <input type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div> -->
                        <div class="sm:col-span-2">
                            <label for="company" class="block text-sm font-semibold leading-6 text-gray-900">Titre de votre article </label>
                            <div class="mt-2.5">
                                <input type="text" name="title" id="title"                      
                                    v-model="form.title"
                                    autocomplete="organization" 
                                    class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                >
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="message" class="block text-sm font-semibold leading-6 text-gray-900">Contenu de votre article</label>
                            <div class="mt-2.5">
                                <textarea name="message" id="message" v-model="form.content" rows="4" 
                                    class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </textarea>
                                <InputError class="mt-2" :message="form.errors.content" />
                            </div>
                        </div>
                        <div class="col-span-full">
                             <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Cover photo</label>
                            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                <div class="text-center">
                                    <img :src="previewImage" class="mx-auto h-40 w-auto" alt="Preview" v-if="previewImage">
                                    <svg v-else class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer rounded-md font-bold text-[15px] text-indigo-600 text-center focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                            <span class="mt-8">Télécharger mon image</span>
                                            <input id="file-upload" name="image" type="file" class="sr-only" @change="handleFileChange" >
                                            <InputError class="mt-2" :message="form.errors.image" />
                                        </label>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF max 2MB</p>
                                </div>
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="options" class="block text-sm font-semibold leading-6 text-gray-900">Choississez une catégorie</label>
                            <MultiSelect 
                                id="options" 
                                v-model="form.categories" 
                                :options="categories_list" 
                                optionLabel="name" 
                                optionValue="id" 
                                class="w-full mt-2.5 rounded-md border-0 px-3.5 py-2  text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                            />
                            <InputError class="mt-2" :message="form.errors.categories" />
                        </div>
                        <!-- <div class="sm:col-span-2">
                            <div class="flex gap-x-4">
                                <div class="flex h-6 items-center">
                                    <input id="agreed" name="agreed" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                </div>
                                <label for="agreed" class="text-sm leading-6 text-gray-600">
                                    By selecting this, you agree to our
                                    <a href="#" class="font-semibold text-indigo-600">privacy policy</a>.
                                </label>
                            </div>
                        </div> -->
                    </div>
                    <div class="mt-10">
                        <button type="submit" class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Let's talk</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>