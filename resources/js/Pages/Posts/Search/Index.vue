<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
      <template v-slot:header>
        <h2 class="font-semibold text-xl">Dashboard</h2>
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
          <div v-if="query.length > 0 && !loading">
            <!-- Affiche les onglets seulement si des posts existent dans au moins un onglet -->
            <div v-if="hasPostsInTabs">
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
            <!-- Affichage d'un message si aucun post n'est disponible -->
            <div v-else>
              <p>Aucun post trouvé pour cette recherche.</p>
            </div>
            <div class="my-8 border-t border-gray-200 dark:border-gray-700"></div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, watch, computed, onMounted } from 'vue';
  import { debounce } from 'lodash';
  import { FwbInput, FwbTab, FwbTabs } from 'flowbite-vue';
  import axios from '../../../axios';
  import { router } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import PostSection from './PostSection.vue';
  import { usePage, Head } from '@inertiajs/vue3';
  
  // Référence pour la requête de recherche de l'utilisateur
  const query = ref('');
  
  // Référence pour suivre l'état de chargement des données
  const loading = ref(true);
  
  // Référence pour stocker les résultats de recherche
  const results = ref({});
  
  // Référence pour l'onglet actif actuellement sélectionné
  const activeTab = ref('Tout');
  
  // Détecter si nous sommes dans l'onglet Tout pour afficher ou non le link voir plus et le bouton de pagination
  const isAllTab = (option) => option === 'Tout';
  
  // Fonction de récupération des résultats avec debouncing pour limiter les appels API
  const fetchResults = debounce(async () => {
    loading.value = true;
    try {
      // Préparation des données de la requête
      const queryValue = query.value;
  
      // Appel API pour obtenir les résultats de recherche
      const response = await axios.get(route('search.posts'), {
        params: { query: queryValue }
      });
  
      if (response.status !== 200) {
        throw new Error('Network response was not ok');
      }
  
      // Mise à jour des résultats
      const data = response.data;
      results.value = data.results || {};
      console.log(results.value);
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
      // Réinitialiser les résultats et l'état de chargement si la requête est vide
      results.value = {};
      loading.value = false;
    }
  });
  
  // Calculé pour vérifier s'il y a des posts dans l'un des onglets
  const hasPostsInTabs = computed(() => {
    return Object.values(results.value).some(result => result.posts && result.posts.length > 0);
  });
  
  // Fonction pour changer l'onglet actif
  const handleSeeMore = (option) => {
    activeTab.value = option;
  };
  </script>
  