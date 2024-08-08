<template>
    <Toast/>
</template>
  
<script setup>
  import { useToast } from "primevue/usetoast";
  import Toast from 'primevue/toast';
  
  const toast = useToast();
  
  const showSuccess = (message) => {
    toast.add({ severity: 'success', summary: 'Succès', detail: message, life: 3000 });
  };
  
  const showError = (message) => {
    toast.add({ severity: 'error', summary: 'Erreur', detail: message, life: 3000 });
  };
  
  // Utilisez les propriétés globales pour exposer les fonctions
  defineExpose({ showSuccess, showError });
</script>
  