<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template v-slot:header>
    </template>

    <div class="p-4 container mx-auto">
      <!-- Section pour la recherche -->
      <div class="mt-8">
        <div class="flex flex-col items-center py-8">
          <p class="text-2xl text-center mb-4">Que recherchez-vous ?</p>
        </div>
        <div class="flex items-center space-x-3">
          <FwbInput
            v-model="query"
            placeholder="Entrez votre requête de recherche"
            size="lg"
            class="text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          >
            <template #prefix>
              <!-- Icône de recherche -->
              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
              </svg>
            </template>
          </FwbInput>
        </div>
      </div>

      <!-- Section pour afficher les résultats de la recherche -->
      <div class="p-4 container mx-auto">
        <!-- Vérifier si une requête a été faite -->
        <div v-if="query.length > 0 && !loading">
          <!-- Si des résultats existent, afficher les onglets -->
          <div v-if="hasPostsInTabs()">
            <FwbTabs v-model="activeTab" class="p-5">
              <!-- Création des onglets dynamiquement -->
              <FwbTab
                v-for="(result, option) in results"
                :key="option"
                :title="option"
                :name="option"
                :disabled="!result.posts || result.posts.length === 0"
              >
                <!-- Contenu des onglets -->
                <div v-if="activeTab === 'Tout'" class="space-y-4">
                  <!-- Section pour les posts de chaque catégorie -->
                  <PostSection
                    v-if="results.Titres && results.Titres.posts.length"
                    :posts="results.Titres.posts"
                    sectionTitle="Titres"
                    :isAllTab="isAllTab(activeTab)"
                    :pagination="results.Titres.pagination"
                    @see-more="handleSeeMore('Titres')"
                  />
                  <PostSection
                    v-if="results.Auteurs && results.Auteurs.posts.length"
                    :posts="results.Auteurs.posts"
                    sectionTitle="Auteurs"
                    :isAllTab="isAllTab(activeTab)"
                    :pagination="results.Auteurs.pagination"
                    @see-more="handleSeeMore('Auteurs')"
                  />
                  <PostSection
                    v-if="results.Catégories && results.Catégories.posts.length"
                    :posts="results.Catégories.posts"
                    sectionTitle="Catégories"
                    :pagination="results.Catégories.pagination"
                    :isAllTab="isAllTab(activeTab)"
                    @see-more="handleSeeMore('Catégories')"
                  />
                </div>
                <div v-else>
                  <!-- Affichage des posts pour l'onglet actif -->
                  <PostSection
                    v-if="results[activeTab] && results[activeTab].posts.length"
                    :posts="results[activeTab].posts"
                    :sectionTitle="activeTab"
                    :isAllTab="isAllTab(activeTab)"
                    :pagination="results[activeTab].pagination"
                    :queryValue="query"
                  />
                </div>
              </FwbTab>
            </FwbTabs>
          </div>
          <!-- Si aucun post n'est trouvé et le chargement est terminé -->
          <div v-else-if="!loading">
            <p class="text-center text-gray-500">Aucun post trouvé pour cette recherche.</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { FwbInput, FwbTab, FwbTabs } from 'flowbite-vue';
import axios from '../../../axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PostSection from './PostSection.vue';
import { Head } from '@inertiajs/vue3';

// Références pour les états de l'application
const query = ref('');
const loading = ref(true);
const results = ref({});
const activeTab = ref('Tout');

// Détecter si nous sommes dans l'onglet Tout pour afficher ou non le link voir plus et le bouton de pagination
const isAllTab = (option) => option === 'Tout';
const isEmptyObject = (obj) => Object.keys(obj).length === 0;

// Fonction de récupération des résultats avec debouncing pour limiter les appels API
const fetchResults = debounce(async () => {
  loading.value = true;
  try {
    const queryValue = query.value;
    const response = await axios.get(route('search.posts'), {
      params: { query: queryValue }
    });

    if (response.status !== 200) {
      throw new Error('Network response was not ok');
    }

    results.value = response.data.results || {};
  } catch (error) {
    console.error('Erreur lors de la récupération des résultats:', error.message);
  } finally {
    loading.value = false;
  }
}, 300);

// Surveillance des changements dans la requête pour effectuer une recherche
watch([query], () => {
  if (query.value.length > 0) {
    fetchResults();
  } else {
    results.value = {};
    loading.value = false;
  }
});

// Fonction pour vérifier s'il y a des posts dans l'un des onglets
const hasPostsInTabs = () => {
  return Object.values(results.value).some(result => result.posts && result.posts.length > 0);
};

// Fonction pour changer l'onglet actif
const handleSeeMore = (option) => {
  activeTab.value = option;
};
</script>
