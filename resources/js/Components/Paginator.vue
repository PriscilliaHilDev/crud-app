<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import { onMounted } from 'vue';

// Définition des props
const props = defineProps({
  pagination: {
    type: Object, // Utilisation d'un objet pour la pagination plutôt qu'un tableau
    required: true
  }
});

// Filtrer les pages numérotées à partir des liens de pagination
const filteredPages = props.pagination.links.filter(link => /^\d+$/.test(link.label));

// Méthode pour obtenir l'URL du premier lien de pagination
const getFirstPageUrl = () => {
  return props.pagination.links.length > 0 ? props.pagination.links[0].url : '#';
};

// Méthode pour obtenir l'URL du dernier lien de pagination
const getLastPageUrl = () => {
  const lastIndex = props.pagination.links.length - 1;
  return lastIndex >= 0 ? props.pagination.links[lastIndex].url : '#';
};
</script>

<template>
  <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 absolute bottom-0 right-0 left-0 ">
    <div class="flex flex-1 justify-between sm:hidden">
      <a :href="getFirstPageUrl()" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
      <a :href="getLastPageUrl()" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Affichage des résultats de
          {{ ' ' }}
          <span class="font-medium">{{ pagination.current_page }}</span>
          {{ ' ' }}
          à
          {{ ' ' }}
          <span class="font-medium">{{ pagination.last_page }}</span>
          {{ ' ' }}
          sur un total de
          {{ ' ' }}
          <span class="font-medium">{{ pagination.total }}</span>
          {{ ' ' }}
          résultats
        </p>
      </div>
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
          <a :href="getFirstPageUrl()" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Previous</span>
            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
          </a>
          <a
            v-for="(link, index) in filteredPages"
            :key="index"
            :href="link.url"
            :class="link.active 
                    ? 'relative inline-flex items-center px-4 py-2 text-sm font-semibold z-10 bg-pink-800 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' 
                    : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'"
          >
            {{ link.label }}
          </a>
          <a :href="getLastPageUrl()" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Next</span>
            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
          </a>
        </nav>
      </div>
    </div>
  </div>
</template>
