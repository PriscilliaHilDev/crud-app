<script setup>
// Importer les fonctions nécessaires depuis Vue et Inertia.js
import { ref, onMounted, computed, onUnmounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';

// Définir les propriétés attendues pour ce composant
const props = defineProps({
  pagination: {
    type: Object, // Le type de la propriété 'pagination' est un objet
    required: true // Cette propriété est requise
  }
});

// Référence pour détecter si l'utilisateur est sur un appareil mobile
const isMobile = ref(window.innerWidth < 768); // Initialiser avec la largeur de la fenêtre

// Calculer le nombre total de pages basé sur la pagination
const totalPages = computed(() => Math.ceil(props.pagination.total / props.pagination.per_page));

// Fonction appelée lors du changement de page par le Paginator
const onPageChange = (event) => {
  const page = event.page + 1; // Ajuster la page pour commencer à 1 au lieu de 0
  const url = `${props.pagination.path}?page=${page}`; // Créer l'URL pour la page demandée
  // Effectuer une requête GET avec Inertia pour charger la nouvelle page sans rafraîchir
  Inertia.get(url, {}, {
    preserveState: true, // Préserver l'état actuel de la page sans recharger
    replace: true, // Remplacer l'historique de la page
    onSuccess: (page) => {
      console.log('Page loaded:', page); // Log lorsque la page est chargée avec succès
    },
    onError: (error) => {
      console.error('Failed to load page:', error); // Log en cas d'échec de chargement de la page
    }
  });
};

// Fonction pour aller à une page spécifique
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) { // Vérifier si la page demandée est dans la plage valide
    const url = `${props.pagination.path}?page=${page}`; // Créer l'URL pour la page demandée
    // Effectuer une requête GET avec Inertia pour charger la nouvelle page sans rafraîchir
    Inertia.get(url, {}, {
      preserveState: true, // Préserver l'état actuel de la page sans recharger
      replace: true, // Remplacer l'historique de la page
      onSuccess: (page) => {
        console.log('Page loaded:', page); // Log lorsque la page est chargée avec succès
      },
      onError: (error) => {
        console.error('Failed to load page:', error); // Log en cas d'échec de chargement de la page
      }
    });
  }
};

// Fonction pour gérer les changements de taille d'écran
const handleResize = () => {
  isMobile.value = window.innerWidth < 768; // Mettre à jour la valeur de isMobile en fonction de la largeur de la fenêtre
};

// Ajouter un écouteur d'événements pour la redimension de la fenêtre lorsque le composant est monté
onMounted(() => {
  window.addEventListener('resize', handleResize); // Écouter les événements de redimensionnement de la fenêtre
});

// Retirer l'écouteur d'événements lorsque le composant est démonté
onUnmounted(() => {
  window.removeEventListener('resize', handleResize); // Retirer l'écouteur pour éviter les fuites de mémoire
});
</script>


<template>
  <div class="absolute bottom-0 right-0 left-0">
    <Paginator
      v-if="!isMobile"
      :rows="pagination.per_page"
      :totalRecords="pagination.total"
      :rowsPerPageOptions="false"
      :first="(pagination.current_page - 1) * pagination.per_page"
      @page="onPageChange"
      class="paginator"
    ></Paginator>

    <div v-else class="flex justify-between items-center p-4">
      <button
        :disabled="pagination.current_page <= 1"
        @click="goToPage(pagination.current_page - 1)"
        class="bg-red-800 text-white px-4 py-2 rounded-lg focus:outline-none disabled:bg-gray-400"
      >
        Previous
      </button>
      <span class="text-pink-800 font-bold px-4 py-2">Page {{ pagination.current_page }} of {{ totalPages }}</span>
      <button
        :disabled="pagination.current_page >= totalPages"
        @click="goToPage(pagination.current_page + 1)"
        class="bg-pink-800 text-white px-4 py-2 rounded-lg focus:outline-none disabled:bg-gray-100"
      >
        Next
      </button>
    </div>
  </div>
</template>

<style>
/* Exemple pour styliser le Paginator avec Tailwind CSS */
.paginator .p-paginator-page.p-highlight {
  @apply bg-pink-500 text-white; /* Couleur rose pour la page active */
}
</style>
