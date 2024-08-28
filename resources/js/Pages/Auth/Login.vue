<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const loginWith = (provider) => {
    window.location.href = `/auth/${provider}`;
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Se souvenir de moi</span>
                </label>
            </div>

            <div class="flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class=" text-sm hover:underline hover:underline-offset-8  text-gray-600 hover:text-pink-700 rounded active:no-underline focus:ring-1 focus:ring-offset-1 focus:underline-none focus:ring-pink-500"
                >
                    Mot de passe oublié ?
                </Link>
            </div>

            <!-- Ajout des classes flex et justify-center pour centrer le bouton -->
            <div class="flex justify-center my-2">
                <PrimaryButton
                    class="w-64 flex items-center justify-center text-center"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Se Connecter
                </PrimaryButton>
            </div>
        </form>

        <!-- Section hors du formulaire -->
        <div class="flex flex-col items-center justify-center my-6">
            <div class="my-2">
                <span class="text-gray-600">ou</span>
            </div>
            <div class="my-2">
                <button
                    @click="loginWith('google')"
                    class="flex items-center justify-center w-64 p-2 text-white bg-red-800 rounded-lg hover:bg-red-800 transition duration-300 ease-in-out"
                >
                    <font-awesome-icon icon="g" size="2x" color="#FFFFFF" class="mr-2" />
                    Connexion avec Google
                </button>
            </div>
        </div>

        <div class="flex flex-inline justify-end my-5">
            <a class="font-bold hover:underline hover:underline-offset-8  text-gray-600 hover:text-pink-700 rounded active:no-underline focus:ring-1 focus:ring-offset-1 focus:underline-none focus:ring-pink-500" :href="route('register')" >
                Je n'ai pas encore de compte ?
            </a>
        </div>
    </GuestLayout>
</template>
