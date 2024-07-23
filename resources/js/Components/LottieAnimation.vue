<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import lottie from 'lottie-web';

// Définir les props acceptées par le composant
const props = defineProps({
  animationData: {
    type: Object,
    required: true,
  },
  loop: {
    type: Boolean,
    default: true,
  },
  autoplay: {
    type: Boolean,
    default: true,
  },
});

// Référence pour le conteneur Lottie
const lottieContainer = ref(null);

let animationInstance;

// Charger l'animation lors du montage du composant
onMounted(() => {
  animationInstance = lottie.loadAnimation({
    container: lottieContainer.value,
    renderer: 'svg',
    loop: props.loop,
    autoplay: props.autoplay,
    animationData: props.animationData,
  });
});

// Détruire l'instance de l'animation lors du démontage du composant
onUnmounted(() => {
  if (animationInstance) {
    animationInstance.destroy();
  }
});

// Regarder les changements dans les props d'animationData
watch(() => props.animationData, (newData) => {
  if (animationInstance) {
    animationInstance.destroy();
    animationInstance = lottie.loadAnimation({
      container: lottieContainer.value,
      renderer: 'svg',
      loop: props.loop,
      autoplay: props.autoplay,
      animationData: newData,
    });
  }
});
</script>

<style scoped>
.lottie-container-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 20vh; /* ou une autre hauteur appropriée */
}

.lottie-container {
  width: 30%;
}
</style>

<template>
  <div class="lottie-container-wrapper">
    <div ref="lottieContainer" class="lottie-container"></div>
  </div>
</template>
