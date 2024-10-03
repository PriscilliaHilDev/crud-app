<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import axios from 'axios';
import { truncateByWords, formatDateAndHour } from '@/Common/utils';
import {
  Dialog,
  DialogPanel,
  Popover,
  PopoverButton,
  PopoverGroup,
  PopoverPanel,
} from '@headlessui/vue';

// const props = defineProps({

//     mobileMenuOpen:{
//         type: Boolean
//     }
// });
// Références pour les notifications et le nombre de non lues
const allNotifications = ref([]);
const unreadCount = ref(0);
const op = ref(null);
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
// const toggle = (event) => {
//   op.value.toggle(event);
//   markAsRead();
// };

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

// Calculer les classes pour le PopoverPanel
const panelClasses = computed(() => {
  return `absolute bg-white py-4 min-w-[300px] my-8 rounded-xl shadow-md ${
    windowWidth.value > 890 ? 'left-0' : '-translate-x-full'
  }`;
});
</script>

<template>
  <div class="flex justify-center items-center">
    <PopoverGroup>
      <Popover class="relative">
        <PopoverButton>
          <OverlayBadge :value="unreadCount" @click="markAsRead" size="small">
            <font-awesome-icon icon="bell" color="grey" />
          </OverlayBadge>
        </PopoverButton>

        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 translate-y-1"
        >
          <PopoverPanel :class="panelClasses">
            <div class="flex flex-col w-[25rem] p-2">
              <span class="font-medium block mb-2">Notifications</span>
              <Divider />
              <div class="max-h-[400px] overflow-y-auto">
                <ul v-if="allNotifications.length > 0" class="list-none p-0 m-0 flex flex-col">
                  <li
                    v-for="notification in allNotifications"
                    :key="notification.id"
                    class="flex items-center gap-2"
                  >
                    <div
                      :class="{
                        'text-gray-500': unreadCount === 0 || notification.read_at,
                        'text-black': unreadCount > 0 && !notification.read_at,
                      }"
                    >
                      <p class="font-bold">
                        <span class="text-sm font-normal">{{ formatDateAndHour(notification.data.createdAt) }}</span>,
                        Nouveau commentaire sur l'article
                        <span class="font-normal italic">« {{ notification.data.post_title }} »</span>
                      </p>
                      <p>
                        <span class="font-bold">{{ notification.data.author }}</span> à écrit :
                        <span class="font-bold"> {{ notification.data.content }}</span>
                      </p>
                      <a :href="route('post.show', { post: notification.data.post })">Voir l'article</a>
                      <div class="text-sm">{{ notification.createdAt }}</div>
                      <Divider />
                    </div>
                  </li>
                </ul>
                <p v-else>Aucune notification</p>
              </div>
            </div>
          </PopoverPanel>
        </transition>
      </Popover>
    </PopoverGroup>
  </div>
</template>
