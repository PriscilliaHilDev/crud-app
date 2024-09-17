<script setup>
import { ref, onMounted, defineExpose } from 'vue';
import axios from 'axios';
import { truncateByWords, formatDateAndHour } from '@/Common/utils';

// Références pour les notifications et le nombre de non lues
const allNotifications = ref([]);
const unreadCount = ref(0);
const op = ref(null);

// Fonction pour afficher/masquer le popover et marquer les notifications comme lues
const toggle = (event) => {
  op.value.toggle(event);
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
        // Mettez à jour votre état ou votre UI avec les notifications reçues
        console.log('Notifications:', data);
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

// Lors du montage, récupérer les notifications et le nombre non lu
onMounted(() => {
  fetchNotifications();
});

// Exposer les méthodes pour qu'elles puissent être appelées depuis d'autres composants
defineExpose({
  fetchNotifications,
});
</script>

<template>
  <div>
    <button @click="toggle">
      <OverlayBadge :value="unreadCount" size="small">
        <font-awesome-icon icon="bell" color="grey" />
      </OverlayBadge>
    </button>
    <Popover ref="op">
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
    </Popover>
  </div>
</template>
