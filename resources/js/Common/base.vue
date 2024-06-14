
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link} from '@inertiajs/vue3';
import { onMounted } from 'vue';


const props = defineProps({
    myPosts: {
        type: Array,
    },
    pagination_links: {
        type: Array,
    }
});

// onMounted(() => {
//     console.log('props', props.myPosts)
// });


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template v-slot:header>
            <h2 class="font-semibold text-xl">Dashboard</h2>
        </template>

        <div v-if="myPosts.length > 0">
           
        </div>

        <div v-else class="max-w-2xl text-center py-20">
            <p class="text-lg leading-8 text-gray-600">Vous n'avez encore écrit aucun article.</p>
        </div>
    </AuthenticatedLayout>
</template>


