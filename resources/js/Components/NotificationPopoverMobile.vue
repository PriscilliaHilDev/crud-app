<script setup>
import { ref, onMounted, onUnmounted} from 'vue';
import axios from 'axios';
import { truncateByWords, formatDateAndHour } from '@/Common/utils';
import {
  Dialog,
  DialogPanel,
} from '@headlessui/vue';
import {
  XMarkIcon,
} from '@heroicons/vue/24/outline';



// Références pour les notifications et le nombre de non lues
const allNotifications = ref([]);
const unreadCount = ref(0);
const mobileMenu = ref(false);
// const op = ref(null);
const windowWidth = ref(window.innerWidth);

// Fonction pour mettre à jour la largeur de la fenêtre
const updateWindowWidth = () => {
  windowWidth.value = window.innerWidth;
};

// Écouter les changements de taille de la fenêtre
onMounted(() => {
  window.addEventListener('resize', updateWindowWidth);
  fetchNotifications();
});

onUnmounted(() => {
  window.removeEventListener('resize', updateWindowWidth);
});

// Fonction pour afficher/masquer le popover et marquer les notifications comme lues
const toggleOpenDial = () => {
  mobileMenu.value = true;
  markAsRead();
};

// Fonction pour marquer toutes les notifications comme lues
const markAsRead = async () => {
  try {
    await axios.post('/notifications/mark-as-read');
    unreadCount.value = 0;
  } catch (error) {
    console.error('Erreur lors de la mise à jour des notifications :', error);
  }
};

// Fonction pour récupérer les notifications
const fetchNotifications = async () => {
  try {
    const response = await fetch('/notifications');
    if (!response.ok) throw new Error(response.status);
    const data = await response.json();
    allNotifications.value = data.notifications;
    unreadCount.value = data.unreadCount;
    console.log('Notifications:', data);
  } catch (error) {
    console.error('Error fetching notifications:', error);
  }
};

// Exposer les méthodes pour qu'elles puissent être appelées depuis d'autres composants
defineExpose({
  fetchNotifications,
});


</script>

<template>
  <div>
    <div class="flex lg:hidden">
      <OverlayBadge :value="unreadCount" @click="toggleOpenDial" size="small">
        <font-awesome-icon icon="bell" color="grey" />
      </OverlayBadge>
    </div>
    <Dialog class="lg:hidden" @close="mobileMenu = false" :open="mobileMenu">
      <div class="fixed inset-0 z-10" />
      <DialogPanel class="fixed inset-y-0 right-0 z-20 w-full h-full bg-white shadow-lg flex flex-col p-4 overflow-hidden">
        <div class="flex justify-between items-center">
          <h2 class="font-medium text-lg">Notifications</h2>
          <button
            type="button"
            class="rounded-md text-gray-700"
            @click="mobileMenu = false"
          >
            <span class="sr-only">Close menu</span>
            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
          </button>
        </div>

        <Divider class="my-2" />

        <div class="flex-grow overflow-y-auto">
          <ul v-if="allNotifications.length > 0" class="list-none p-0 m-0 flex flex-col">
            <li
              v-for="notification in allNotifications"
              :key="notification.id"
              class="flex flex-col gap-2 p-2 border-b border-gray-200"
            >
              <div :class="{
                'text-gray-500': unreadCount === 0 || notification.read_at,
                'text-black': unreadCount > 0 && !notification.read_at
              }" class="text-center">
                <p>
                  <span class="text-sm">{{ formatDateAndHour(notification.data.createdAt) }}</span>,
                  Nouveau commentaire sur l'article
                  <span class="font-bold italic">« {{ notification.data.post_title }} »</span>
                </p>
                <p>
                  <span class="font-bold">{{ notification.data.author }}</span> à écrit :
                  <span class="font-bold">{{ notification.data.content }}</span>
                </p>
                <a :href="route('post.show', { post: notification.data.post })" class="text-blue-600 hover:underline">Voir l'article</a>
                <div class="text-sm">{{ notification.createdAt }}</div>
              </div>
            </li>
          </ul>
          <p v-else class="text-center text-gray-500">Aucune notification</p>
        </div>
      </DialogPanel>
    </Dialog>
  </div>
</template>
