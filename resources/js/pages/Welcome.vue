<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import { AlertCircle, X } from 'lucide-vue-next'
import { ref, onMounted } from 'vue'
import WelcomeBookDialog from '@/components/WelcomeBookDialog.vue';
import WelcomeSearch from '@/components/WelcomeSearch.vue';
import LoginModal from '@/components/LoginModal.vue';
import RegisterModal from '@/components/RegisterModal.vue';

// for alert
const page = usePage()
const showAlert = ref(true)
onMounted(() => {
    if (page.props.flash.error) {
        setTimeout(() => {
            showAlert.value = false
        }, 5000)
    }
})

interface Flash {
    success?: string | null;
    error?: string | null;
}

interface Config {
    registration_enabled?: boolean | null
    login_enabled?: boolean | null
}

declare module '@inertiajs/core' {
    interface PageProps {
        flash: Flash;
        config: Config;
    }
}

defineProps({
    records: Object,
    search_result: Object,
    search_term: String,
    search_button: Boolean,
});

const showLoginModal = ref(false);

const openLoginModal = () => {
    showLoginModal.value = true;
};
const closeLoginModal = () => {
    showLoginModal.value = false;
};

const showRegisterModal = ref(false);

const openRegisterModal = () => {
    showRegisterModal.value = true;
};
const closeRegisterModal = () => {
    showRegisterModal.value = false;
};

</script>

<template>
    <Head title="Welcome Raffy" />
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50 w-full">
        <div class="container mx-auto px-4 py-3 flex flex-col md:flex-row justify-between items-center gap-4">
            <Link href="/logger" class="text-2xl font-bold text-usepmaroon hover:text-usepmaroon/80 transition flex items-center">
                <img src="/images/usep-logo-small.png" alt="USEP Logo" class="h-8 mr-2">
                USeP Library
            </Link>
            <div class="flex items-center space-x-4">
                <button v-if="$page.props.config.login_enabled" @click="openLoginModal" class="px-4 py-2 border border-usepmaroon text-usepmaroon rounded-md hover:bg-usepmaroon/10 transition flex items-center shadow-sm hover:shadow">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    <span class="hidden md:inline">Login</span>
                </button>
                <button v-if="$page.props.config.registration_enabled" @click="openRegisterModal" class="px-4 py-2 border border-usepgold text-usepgold rounded-md hover:bg-usepgold/10 transition flex items-center shadow-sm hover:shadow">
                    <i class="fas fa-user-plus mr-2"></i>
                    <span class="hidden md:inline">Register</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-[45vh] bg-cover bg-center flex flex-col items-center justify-center mb-8 hero-pattern"
             style="background-image: url('/images/eagle.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 text-center text-white">
            <h1 class="text-3xl font-bold">USeP Campus Library</h1>
            <p class="text-lg mt-2">Tagum-Mabini</p>
        </div>
        <!-- Search Bar -->
        <div class="relative z-60 w-full px-4 mt-8 flex justify-center">
            <div class="flex-1 max-w-3xl w-full relative flex justify-center">
                <WelcomeSearch :search_result="search_result" :search_term="search_term" :search_button="search_button" />
            </div>
        </div>
    </section>

    <!-- Collection Cards Section -->
    <section class="py-12 bg-gray-50 -mt-14">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 max-w-7xl mx-auto px-4 mt-5px">
                <div class="collection-card group relative bg-gradient-to-br from-white to-gray-50 p-8 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:border-usepmaroon/20"
                    @click="openLoginModal">
                    <!-- Browse Collection Card -->
                    <div class="absolute top-0 right-0 w-16 h-16 bg-usepmaroon/5 transform translate-x-8 -translate-y-8 rotate-45 transition-all duration-500 group-hover:bg-usepmaroon/10"></div>
                    <div class="relative z-10">
                        <div class="text-usepmaroon text-4xl mb-4 transition-all duration-300 group-hover:text-usepmaroon/90 group-hover:scale-110 inline-block">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-800 group-hover:text-usepmaroon transition-colors duration-300">Browse Collection</h3>
                        <div class="text-3xl font-bold text-usepmaroon count-animation mb-1">25,689</div>
                        <p class="text-gray-500 mt-2 text-sm font-medium">Items available</p>
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="inline-block text-usepmaroon/80 text-sm font-medium">Click for details
                                <i class="fas fa-arrow-right ml-1 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="collection-card group relative bg-gradient-to-br from-white to-gray-50 p-8 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:border-usepmaroon/20"
                    @click="openRegisterModal">
                    <!-- New Arrivals Card -->
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-yellow-400 rounded-full opacity-0 group-hover:opacity-70 group-hover:animate-ping duration-1000"></div>
                        <div class="absolute top-1/3 right-1/4 w-1.5 h-1.5 bg-yellow-400 rounded-full opacity-0 group-hover:opacity-60 group-hover:animate-ping duration-700 delay-200"></div>
                    </div>
                    <div class="relative z-10">
                        <div class="text-usepmaroon text-4xl mb-4 transition-all duration-300 group-hover:text-yellow-500 group-hover:scale-110 inline-block">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-800 group-hover:text-usepmaroon transition-colors duration-300">New Arrivals</h3>
                        <div class="text-3xl font-bold text-usepmaroon count-animation mb-1">1,245</div>
                        <p class="text-gray-500 mt-2 text-sm font-medium">New items this month</p>
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="inline-block text-usepmaroon/80 text-sm font-medium">Click for details
                                <i class="fas fa-arrow-right ml-1 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="collection-card group relative bg-gradient-to-br from-white to-gray-50 p-8 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:border-usepmaroon/20"
                    @click="openLoginModal">
                    <!-- Top Picks Card -->
                    <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-usepmaroon/5 rounded-full transform transition-all duration-500 group-hover:scale-150 group-hover:bg-usepmaroon/10"></div>
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-usepmaroon/5 rounded-full transform transition-all duration-500 group-hover:scale-150 group-hover:bg-usepmaroon/10"></div>
                    <div class="relative z-10">
                        <div class="text-usepmaroon text-4xl mb-4 transition-all duration-300 group-hover:text-green-500 group-hover:scale-110 inline-block">
                            <i class="fas fa-thumbs-up"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-800 group-hover:text-usepmaroon transition-colors duration-300">Top Picks</h3>
                        <div class="text-3xl font-bold text-usepmaroon count-animation mb-1">892</div>
                        <p class="text-gray-500 mt-2 text-sm font-medium">Highly recommended</p>
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="inline-block text-usepmaroon/80 text-sm font-medium">Click for details
                                <i class="fas fa-arrow-right ml-1 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Book Collection Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-usepmaroon mb-12">Book Collection</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 px-4">
                <div v-for="record in records?.data" :key="record.id">
                    <WelcomeBookDialog :record="record" />
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-usepmaroon text-white py-8 w-full">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-xl font-bold">USEP Library</h3>
                    <p class="text-usepgold/80">Tagum-Mabini Campus</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-white hover:text-usepgold transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-white hover:text-usepgold transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-white hover:text-usepgold transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
            <div class="border-t border-usepgold/20 mt-6 pt-6 text-sm text-center text-usepgold/80">
                &copy; 2023 USEP Library. All rights reserved.
            </div>
        </div>
    </footer>

    <LoginModal v-if="showLoginModal" @close="closeLoginModal" />
    <RegisterModal v-if="showRegisterModal" @close="closeRegisterModal" />
</template>

<style scoped>
.hero-pattern {
    background-image: radial-gradient(rgba(0, 0, 0, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}
.collection-card {
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    z-index: 10;
}
.collection-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    z-index: 20;
}
.count-animation {
    animation: pulse 1s infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
</style>
