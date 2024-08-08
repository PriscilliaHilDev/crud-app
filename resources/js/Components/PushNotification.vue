
   <template>
    <Toast :position="isPush ? 'bottom-right' : 'top-right' ">
        <template #message="slotProps">
            <div class="flex flex-col items-start flex-auto">
                <h2 class="w-full text-center font-bold text-lg mb-2 text-pink-950">Nouveau Commentaire</h2>
                <h3 class="w-full text-sm font-bold text-pink-800">{{ slotProps.message.summary }}</h3>
                <div class="text-justify my-2 text-gray-700">
                    {{ truncateByWords(slotProps.message.detail, 15) }}
                </div>
                <a :href="route('post.show', { post: post})"
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
const isPush = ref(false);

const showToast = (message) => {
    toast.add({ severity: 'success', summary: 'Succès', detail: message, life: 5000});
};

const toastPassthroughOptions = {
    summary: { class: 'bg-pink-100' }, // Custom class for summary
    message: { class: 'custom-toast-message' }, // You can add other custom styles here
};

const showPush = (author, message, datetime) => {
    isPush.value = true; // Indicate that this is a push notification

    // Convert datetime to a JavaScript Date object
    const date = new Date(datetime);

    // Format the date to a readable time string (e.g., HH:mm)
    const options = { hour: '2-digit', minute: '2-digit', hour12: false };
    const timeString = date.toLocaleTimeString('fr-FR', options); // You can change 'fr-FR' to your preferred locale

    toast.add({
        severity: 'secondary', // Changed 'secondary' to 'info' as per PrimeVue standards
        summary: `À ${timeString}, ${author} a commenté :`, // Include the formatted time
        detail: message, 
        life: 5000, 
        pt: toastPassthroughOptions // Pass the custom passthrough options here
    });
};

// Exposer les fonctions pour les utiliser dans d'autres composants
defineExpose({ showToast, showPush });
</script>
