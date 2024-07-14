<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, defineExpose} from 'vue';
import {
  Dialog,
  DialogPanel,
  Popover,
  PopoverButton,
  PopoverGroup,
  PopoverPanel,
} from '@headlessui/vue';
import {
  XMarkIcon,
} from '@heroicons/vue/24/outline';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import Paginator from "@/Components/Paginator.vue";
import { truncateByWords, formatDate } from '@/Common/utils';



// detected la version mobile pour le responvise design du menu des cards
const mobileMenuOpen = ref(false);

// definition des proprietes du composant
const props = defineProps({
  posts: {
    type: Array,
    required: true,
  },
  pagination_links: {
    type: Array,
    required: true,
  },

});
onMounted(() => {
console.log(props.posts)
});

</script>

<template>

  
    <Head title="Accueil" />

    <AuthenticatedLayout>
       <template #header>
            <h2 class="font-semibold text-xl "></h2>
        </template>
        
         <!-- <div class="flex justify-center items-center bg-cover bg-center w-full h-screen" :class="'bg-[url(' + posts[1].image + ')'"></div> -->
         <div v-if="posts.length > 0" class="py-20">
          <!-- <Toast/>
          <Link href="" @click="show">kk</Link> -->
            <div class="grid container mx-auto grid-cols-1 sm:grid-cols-2 gap-4 max-w-7xl px-6 lg:px-8 xl:grid xl:grid-cols-2 xl:gap-x-10 xl:gap-y-4 ">
              <div
                class="relative isolate overflow-hidden container mx-auto my-8 h-[400px] max-w-2xl w-full lg:rounded-3xl"
                v-for="postItem in posts"
                :key="postItem.id"
              >
                <header class="relative z-20">
                  <div class="container mx-auto">
                    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
                      <PopoverGroup class="hidden lg:flex lg:gap-x-12 mx-8">
                        <Popover class="relative">
                          <PopoverButton class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 border-none focus:outline-none">
                            Catégories
                            <ChevronDownIcon class="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
                          </PopoverButton>
                          <transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-1"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 translate-y-1"
                          >
                            <PopoverPanel class="absolute -left-8 top-full z-10 mt-3 w-screen max-w-md overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                              <div class="p-4 overflow-y-auto" style="max-height: 20rem;">
                                <ul class="text-center font-bold" v-for="category in postItem.categories" :key="category.id">
                                  <li class="my-2 py-2 text-pink-900 bg-pink-100 rounded-lg">{{ category.name }}</li>
                                </ul>
                              </div>
                            </PopoverPanel>
                          </transition>
                        </Popover>
                      </PopoverGroup>
                      <div class="flex lg:hidden">
                        <button
                          type="button"
                          class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                          @click="mobileMenuOpen = true"
                        >
                          <span class="sr-only">Open main menu</span>
                          <p class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 border-none focus:outline-none">
                            Catégories
                          </p>
                        </button>
                      </div>
                      <div class="flex justify-end">
                        <Link class="-m-1.5 p-1.5" :href="route('post.show', { post: postItem })">
                          <font-awesome-icon icon="eye" size="2x" color="#B9255F" />
                        </Link>
                      </div>
                    </nav>
                    <Dialog class="lg:hidden" @close="mobileMenuOpen = false" :open="mobileMenuOpen">
                      <div class="fixed inset-0 z-10" />
                      <DialogPanel class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                        <div class="flex justify-end">
                          <button
                            type="button"
                            class="-m-2.5 rounded-md p-2.5 text-gray-700"
                            @click="mobileMenuOpen = false"
                          >
                            <span class="sr-only">Close menu</span>
                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                          </button>
                        </div>
                        <div class="mt-6 flow-root">
                          <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="space-y-2 py-6">
                              <ul class="text-center font-bold" v-for="category in postItem.categories" :key="category.id">
                                <li class="my-2 py-2 text-pink-900 bg-pink-50 rounded-lg">{{ category.name }}</li>
                              </ul>
                            </div>
                            <div class="flex justify-center flex-col items-center">
                              <p class="py-2"> Auteur : {{ postItem.user.name }}</p>
                              <p class="py-2"> Publié le {{ formatDate(postItem.created_at) }}</p>
                            </div>
                          </div>
                        </div>
                      </DialogPanel>
                    </Dialog>
                  </div>
                </header>
                <img
                  :src="postItem.image ? '/storage' + postItem.image.path : '/storage/images/default-image.jpg'"
                  alt="image de l'article"
                  class="absolute inset-0 -z-10 h-full w-full object-cover object-center"
                />
                <div
                  class="hidden sm:absolute sm:-top-10 sm:right-1/2 sm:-z-10 sm:mr-10 sm:block sm:transform-gpu sm:blur-3xl"
                  aria-hidden="true"
                >
                  <div
                    class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-20"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                  />
                </div>
                <div
                  class="absolute -top-52 left-1/2 -z-10 -translate-x-1/2 transform-gpu blur-3xl sm:top-[-28rem] sm:ml-16 sm:translate-x-0 sm:transform-gpu"
                  aria-hidden="true"
                >
                  <div
                    class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-20"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                  />
                </div>
                <div class="absolute inset-0 bg-pink-100 opacity-50"></div>
                <div
                  class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10 py-10 sm:py-10 flex flex-col items-center text-center"
                >
                  <div class="mx-auto max-w-2xl">
                    <h2 class="font-bold tracking-tight text-slate-600 text-2xl sm:text-2xl lg:text-3xl xl:text-4xl">
                      {{ truncateByWords(postItem.title, 7) }}
                    </h2>
                    <p class="mt-6 sm:text-md lg:text-lg xl:text-xl leading-6 sm:leading-7 lg:leading-8 xl:leading-9 text-slate-600">
                      {{ truncateByWords(postItem.content, 18) }}
                    </p>
                  </div>
                </div>
                <div class="absolute bottom-0 right-2 z-30 p-4 rounded-lg text-right hidden lg:block">
                  <p class="py-2 font-bold text-slate-800 text-md">Auteur : {{ postItem.user.name }}</p>
                  <p class="py-2 text-slate-800 text-sm">Publié le {{ formatDate(postItem.created_at) }}</p>
                </div>
              </div>
            </div>
        <Paginator :pagination="pagination_links" />
      </div>
      <div v-else>
        <h3 class="text-3xl text-pink-800 font-bold py-5 text-center"> Aucuns articles trouvés </h3>
      </div>
    </AuthenticatedLayout>
</template>
