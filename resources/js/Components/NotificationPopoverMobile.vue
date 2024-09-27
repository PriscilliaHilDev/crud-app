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
            <DialogPanel class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex justify-end">
                    <button
                    type="button"
                    class="-m-2.5 rounded-md p-2.5 text-gray-700"
                    @click="mobileMenu = false"
                    >
                    <span class="sr-only">Close menu</span>
                    <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="flex flex-col w-[25rem] p-2">
                            <span class="font-medium block mb-2">Notifications</span>
                            <Divider />
                            <div class="max-h-[400px] overflow-y-auto">
                                <ul v-if="allNotifications.length > 0" class="list-none p-0 m-0 flex flex-col">
                                    <li v-for="notification in allNotifications" :key="notification.id" class="flex items-center gap-2">
                                        <div :class="{
                                            'text-gray-500': unreadCount === 0 || notification.read_at,
                                            'text-black': unreadCount > 0 && !notification.read_at
                                        }">
                                            <p class="font-bold"> <span class="text-sm font-normal">{{ formatDateAndHour(notification.data.createdAt)}}</span>, Nouveau commentaire sur l'article  <span class="font-normal italic">« {{ notification.data.post_title }} »</span></p>
                                            <p><span class="font-bold">{{ notification.data.author}}</span> à écrit : <span class="font-bold"> {{ notification.data.content }}</span> </p>
                                            <a :href="route('post.show', { post: notification.data.post })">Voir l'article</a>
                                            <div class="text-sm">{{ notification.createdAt }}</div>
                                            <Divider />
                                        </div>
                                    </li>
                                </ul>
                                <p v-else>Aucune notification</p>
                            </div>
                        </div>
                    </div>
                </div>
            </DialogPanel>
        </Dialog>
    </div>
</template>
