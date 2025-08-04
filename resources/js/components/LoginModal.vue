<script setup lang="ts">
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const emit = defineEmits(['close']);

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submitting = ref(false);
const errorMessage = ref('');

const submit = () => {
  submitting.value = true;
  errorMessage.value = '';
  form.post(route('login'), {
    onFinish: () => {
      submitting.value = false;
      if (form.hasErrors && (form.errors.email || form.errors.password)) {
        errorMessage.value = 'Invalid email or password.';
      } else {
        emit('close');
      }
    },
    onError: () => {
      submitting.value = false;
      errorMessage.value = 'Invalid email or password.';
    },
    preserveScroll: true,
  });
};

const handleKeydown = (e: KeyboardEvent) => {
  if (e.key === 'Escape') emit('close');
};
</script>

<template>
  <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50" @click.self="emit('close')" @keydown.esc="emit('close')" tabindex="-1">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8 relative animate-fadeIn" @keydown.stop>
      <button class="absolute top-3 right-3 text-gray-400 hover:text-usepmaroon text-2xl" @click="emit('close')" aria-label="Close">
        &times;
      </button>
      <h2 class="text-2xl font-bold text-usepmaroon mb-6 text-center">Login to Your Account</h2>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="form.email" type="email" required autocomplete="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon" />
          <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input v-model="form.password" type="password" required autocomplete="current-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon" />
          <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
        </div>
        <div class="flex items-center justify-between">
          <label class="flex items-center text-sm">
            <input v-model="form.remember" type="checkbox" class="h-4 w-4 text-usepmaroon focus:ring-usepmaroon border-gray-300 rounded" />
            <span class="ml-2">Remember me</span>
          </label>
          <a :href="route('password.request')" class="text-sm text-usepmaroon hover:underline">Forgot password?</a>
        </div>
        <div v-if="errorMessage" class="text-red-500 text-xs mt-1 text-center">{{ errorMessage }}</div>
        <button type="submit" :disabled="submitting" class="w-full py-2 px-4 bg-usepmaroon text-white rounded-md hover:bg-usepmaroon/90 transition font-medium flex items-center justify-center">
          <span v-if="submitting" class="animate-spin mr-2">⏳</span>
          Login
        </button>
      </form>
      <div class="mt-4 text-center text-sm text-gray-600">
        Don't have an account? <span class="text-usepmaroon font-medium">Contact librarian</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}
</style>
