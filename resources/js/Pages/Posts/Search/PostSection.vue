<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { truncateByWords } from '@/Common/utils';
import { Link } from '@inertiajs/vue3';

// Définition des props acceptées par le composant
const props = defineProps({
  posts: {
    type: Array,
    required: true
  },
  sectionTitle: {
    type: String,
    required: true
  },
  limit: {
    type: Number,
    default: 3
  },
  isAllTab: {
    type: Boolean,
    default: false
  },
  queryValue: {
    type: String
  },
  pagination: {
    type: Object,
    required: false,
    default: () => ({})
  }
});

// Références locales
const posts = ref([...props.posts]);
const loading = ref(false);
const page = ref(props.pagination.current_page || 1);
const hasMore = ref(props.pagination.current_page < props.pagination.last_page);

// Propriété calculée pour `postSeen`
const postSeen = computed(() => {
  const currentPage = props.pagination.current_page || 1;
  const perPage = props.pagination.per_page || 10;
  return (currentPage - 1) * perPage + (posts.value.length || 0);
});

// Fonction pour charger les posts initiaux
const loadPosts = async (pageNumber = 1) => {
  loading.value = true;
  try {
    const response = await axios.get(route('search.posts.page'), {
      params: {
        query: props.queryValue,
        category: props.sectionTitle,
        page: pageNumber
      }
    });

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

// Fonction pour charger plus de posts
const loadMorePosts = () => {
  if (!loading.value && hasMore.value) {
    loadPosts(page.value + 1);
  }
};

// Charger les posts initiaux au chargement du composant
onMounted(() => {
  loadPosts(page.value);
});

// Surveiller les changements dans les props pour mettre à jour les posts et la pagination
watch(() => props.posts, (newPosts) => {
  posts.value = [...newPosts];
  page.value = props.pagination.current_page || 1;
  hasMore.value = props.pagination.current_page < props.pagination.last_page;
}, { immediate: true });

// Définition des événements émis par ce composant
const emit = defineEmits(['see-more']);

// Fonction pour émettre l'événement 'see-more' lorsque l'utilisateur clique sur "Voir plus"
const onSeeMore = () => {
    emit('see-more');
}
</script>
<template>
  <div>
    <!-- En-tête de la section -->
    <div class="flex items-center justify-start space-x-4">
      <h3 class="text-lg font-semibold">{{ sectionTitle }}</h3>
      <span>({{ pagination.total }} articles trouvés)</span>
      <button v-if="isAllTab && posts.length > limit" @click="onSeeMore" class="text-blue-500">
        Voir plus
      </button>
    </div>

    <!-- Liste des posts -->
    <div v-if="posts && posts.length > 0" class="flex flex-wrap p-5 ">
      <div v-for="post in posts" :key="post.id" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-4">
        <Link rel="stylesheet":href="route('post.show', { post: post })">
          <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col h-full">
            <!-- En-tête de la carte avec une image -->
            <img alt="user header" :src="'/storage/' + post.image.path" class="w-full h-40 object-cover" />
            <!-- Contenu de la carte -->
            <div class="flex flex-col flex-grow p-6">
              <h2 class="text-xl font-semibold text-gray-800 truncate">{{ post.title }}</h2>
              <p class="text-gray-600 mt-2 flex-grow">{{ truncateByWords(post.content, 10) }}</p>
              <span> Par {{ post.user.name }}</span>
            </div>
            <!-- Pied de page de la carte avec des boutons -->
            <div v-if="post.categories.length > 0" class="flex flex-wrap gap-2 p-6 bg-gray-100">
              <div v-for="category in post.categories" :key="category.id" class="bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-sm">
                {{ category.name }}
              </div>
            </div>
          </div>
      </Link>
      </div>
    </div>
    <!-- Bouton pour charger plus de posts -->
    <div v-if="!loading && !isAllTab" class="flex flex-col justify-center items-center p-10 my-8">
      <!-- Affichage du nombre d'articles vus et total -->
      <span class="text-gray-700 text-base">
        <strong class="text-blue-600">{{ postSeen }}</strong> articles vus sur 
        <strong class="text-blue-600">{{ pagination.total }}</strong>
      </span>
      <button v-if='hasMore' @click="loadMorePosts" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
        Voir plus
      </button>
    </div>

    <!-- Message de chargement -->
    <div v-if="loading" class="text-gray-700 p-4">Chargement...</div>
  </div>
</template>
