<template>
    <Toast :position="isPush ? 'bottom-right' : 'top-right' ">
      <template #message="slotProps">
        <div class="flex flex-col items-start flex-auto">
          <h2 v-if="isPush" class="w-full text-center font-bold text-lg mb-2 text-pink-950">
            Nouveau Commentaire
          </h2>
          <h3 class="w-full text-sm font-bold text-pink-800">{{ slotProps.message.summary }}</h3>
          <div class="text-justify my-2 text-gray-700">
            {{ truncateByWords(slotProps.message.detail, 15) }}
          </div>
          <a :href="route('post.show', { post: post })"
            class="w-full mx-auto bg-pink-500 text-white text-center p-2 rounded-md hover:bg-pink-600 transition duration-200"
            v-if="isPush"
          >
            Voir l'article
          </a>
        </div>
      </template>
    </Toast>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useToast } from "primevue/usetoast";
  import Toast from 'primevue/toast';
  import { truncateByWords } from '@/Common/utils';
  
  // Définition des props
  const props = defineProps({
    post: {
      type: Object,
    },
  });
  
  const toast = useToast();
  const isPush = ref(false); // Cette variable va contrôler si c'est une notification push ou un toast classique
  
  // Fonction pour montrer un toast classique
  const showToast = (message) => {
    isPush.value = false; // Indiquer que ce n'est pas un push
    toast.add({
      severity: 'success',
      summary: 'Succès',
      detail: message,
      life: 5000
    });
  };
  
  // Fonction pour montrer une notification push
  const showPush = (author, message, datetime) => {
    isPush.value = true; // Indiquer que c'est un push
  
    // Convertir la date et l'heure en un objet Date JavaScript
    const date = new Date(datetime);
  
    // Formater la date en une chaîne lisible
    const options = { hour: '2-digit', minute: '2-digit', hour12: false };
    const timeString = date.toLocaleTimeString('fr-FR', options); // Utilisation du format 'fr-FR'
  
    toast.add({
      severity: 'info', // Sévérité différente pour les push
      summary: `À ${timeString}, ${author} a commenté :`,
      detail: message,
      life: 5000,
      // Custom classes et options spécifiques pour le push
      pt: {
        summary: { class: 'bg-blue-100' },
        message: { class: 'custom-toast-message' },
      }
    });
  };
  
  // Exposer les fonctions pour les utiliser dans d'autres composants
  defineExpose({ showToast, showPush });
  </script>
  
  <style>
  /* Styles spécifiques pour différencier les notifications */
  .p-toast-message-info {
    background-color: #d0ebff; /* Couleur spécifique pour les notifications "push" */
  }
  .p-toast-message-success {
    background-color: #dff0d8; /* Couleur spécifique pour les notifications de succès */
  }
  </style>
  