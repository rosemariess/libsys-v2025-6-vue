<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close']);

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submitting = ref(false);

const submit = () => {
  submitting.value = true;
  form.post(route('register'), {
    onFinish: () => {
      submitting.value = false;
      emit('close');
    },
    onError: () => {
      submitting.value = false;
    },
    preserveScroll: true,
  });
};
</script>

<template>
  <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50" @click.self="emit('close')" @keydown.esc="emit('close')" tabindex="-1">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8 relative animate-fadeIn" @keydown.stop>
      <button class="absolute top-3 right-3 text-gray-400 hover:text-usepmaroon text-2xl" @click="emit('close')" aria-label="Close">
        &times;
      </button>
      <h2 class="text-2xl font-bold text-usepmaroon mb-6 text-center">Register</h2>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
          <input v-model="form.first_name" type="text" required autocomplete="given-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon" />
          <div v-if="form.errors.first_name" class="text-red-500 text-xs mt-1">{{ form.errors.first_name }}</div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
          <input v-model="form.last_name" type="text" required autocomplete="family-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon" />
          <div v-if="form.errors.last_name" class="text-red-500 text-xs mt-1">{{ form.errors.last_name }}</div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="form.email" type="email" required autocomplete="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon" />
          <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input v-model="form.password" type="password" required autocomplete="new-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon" />
          <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input v-model="form.password_confirmation" type="password" required autocomplete="new-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon" />
        </div>
        <button type="submit" :disabled="submitting" class="w-full py-2 px-4 bg-usepmaroon text-white rounded-md hover:bg-usepmaroon/90 transition font-medium flex items-center justify-center">
          <span v-if="submitting" class="animate-spin mr-2">⏳</span>
          Register
        </button>
      </form>
      <div class="mt-4 text-center text-sm text-gray-600">
        Already have an account? <span class="text-usepmaroon font-medium">Login</span>
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
