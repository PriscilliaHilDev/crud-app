<template>
  <div>
    <!-- Liste des posts -->
    <div v-for="post in posts" :key="post.id" class="post">
      <h2>{{ post.title }}</h2>
      <p>{{ post.content }}</p>
    </div>

    <!-- Bouton pour charger plus de posts -->
    <div v-if="!loading && hasMore" class="load-more-container">
      <button @click="loadMorePosts" class="load-more-button">
        Voir plus
      </button>
    </div>

    <!-- Message de chargement -->
    <div v-if="loading" class="loading">Chargement...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const posts = ref([]);
const loading = ref(false);
const page = ref(1);
const hasMore = ref(true);

// Fonction pour charger les posts initiaux
const loadPosts = async (pageNumber = 1) => {
  loading.value = true;
  try {
    const response = await axios.get(`/data-test?page=${pageNumber}`);

    if (response.status !== 200) {
      throw new Error('Erreur lors de la récupération des posts');
    }

    const data = response.data.results;
    posts.value = pageNumber === 1 ? data.data : [...posts.value, ...data.data];
    page.value = pageNumber;
    hasMore.value = data.current_page < data.last_page;
  } catch (error) {
    console.error('Erreur lors de la récupération des posts:', error.message);
  } finally {
    loading.value = false;
  }
};

// Charger les posts initiaux au chargement du composant
onMounted(() => {
  loadPosts();
});

// Fonction pour charger plus de posts
const loadMorePosts = () => {
  if (!loading.value && hasMore.value) {
    loadPosts(page.value + 1);
  }
};
</script>

<style scoped>
.post {
  border: 1px solid #ccc;
  padding: 16px;
  margin-bottom: 8px;
}

.load-more-container {
  text-align: center;
  margin-top: 16px;
}

.load-more-button {
  padding: 8px 16px;
  font-size: 16px;
  cursor: pointer;
}

.loading {
  text-align: center;
  margin: 20px;
}
</style>
