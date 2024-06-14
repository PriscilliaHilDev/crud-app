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
            <div class="flex items-center justify-center my-6">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Se Connecter
                </PrimaryButton>
            </div>
        </form>
        <div class="flex flex-inline justify-end my-5">
            <a class="font-bold hover:underline hover:underline-offset-8  text-gray-600 hover:text-pink-700 rounded active:no-underline focus:ring-1 focus:ring-offset-1 focus:underline-none focus:ring-pink-500" :href="route('register')" >
                Je n'ai pas encore de compte ?
            </a>
        </div>
        <!-- <div class="group/write my-5 mx-0">
            <span class="group-hover/write:invisible transition ">
                Je n'ai pas encore de compte ?
            </span>
            <span class="group/link invisible group-hover/write:visible">
                S'inscrire
            </span>
        </div> -->
    </GuestLayout>
</template>
