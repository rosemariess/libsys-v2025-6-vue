<script setup lang="ts">
import { Search } from "lucide-vue-next"
import { Input } from "@/components/ui/input"
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import WelcomeBookDialog from '@/components/WelcomeBookDialog.vue';

const props = defineProps({
    search_result: Object,
    search_term: String,
});

const form = useForm({
    search: props.search_term,
    filter: 'All',
});

const filterOptions = [
    'All',
    'Books',
    'Electronic Collection',
    'Periodical Magazine',
    'Thesis and Dissertation',
];

const suggestions = ref([]);
const dropdownRef = ref<HTMLElement | null>(null);
const selectedRecord = ref(null);
const showDialog = ref(false);
const searchFocused = ref(false);

// Watch for changes in the search input or filter and fetch suggestions dynamically
watch([
    () => form.search,
    () => form.filter
], ([searchVal, filterVal]) => {
    if (searchVal && searchVal.length > 0) {
        router.get(route('home'), { search: searchVal, filter: filterVal }, {
            preserveState: true,
            preserveScroll: true,
            only: ['search_result'],
            onSuccess: (page) => {
                const data = page.props.search_result?.data || page.props.search_result || [];
                suggestions.value = data;
            }
        });
    } else {
        suggestions.value = [];
    }
}, { immediate: true });

// Open modal directly when dropdown changes and suggestions exist
watch(() => form.filter, (newVal, oldVal) => {
    if (suggestions.value.length > 0) {
        selectedRecord.value = suggestions.value[0];
        showDialog.value = true;
    }
});
const removeSuggestion = (id: number) => {
    suggestions.value = suggestions.value.filter((item: any) => item.id !== id);
};

// Close dropdown when clicking outside
const handleClickOutside = (event: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        suggestions.value = [];
    }
};

const openBookDialog = (record) => {
    selectedRecord.value = record;
    showDialog.value = true;
};

onMounted(() => {
    document.addEventListener('mousedown', handleClickOutside);
});
onBeforeUnmount(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});
</script>

<template>
    <div class="relative w-full max-w-2xl items-center" ref="dropdownRef">
        <div class="flex relative w-full">
            <select v-model="form.filter" class="rounded-l-md border border-gray-300 bg-white px-3 h-12 focus:outline-none focus:ring-2 focus:ring-usepmaroon min-w-[180px]">
                <option v-for="option in filterOptions" :key="option" :value="option">{{ option }}</option>
            </select>
            <div class="flex-1 relative">
                <Input v-model="form.search" id="search" type="search"
                       placeholder="Search accession, title..." class="pl-10 rounded-l-none w-full min-w-[250px] md:min-w-[350px] lg:min-w-[400px] h-12"
                       @focus="searchFocused = true" @blur="searchFocused = false" />
                <span class="absolute left-2 top-1/2 -translate-y-1/2 flex items-center justify-center">
                  <Search class="size-6 text-muted-foreground" />
                </span>
                <div v-if="searchFocused && suggestions.length" class="absolute left-0 right-0 z-10 mt-2 bg-white border rounded shadow max-h-60 overflow-auto">
                    <div v-for="result in suggestions" :key="result.id" class="p-2 hover:bg-gray-100 cursor-pointer" @mousedown.prevent="openBookDialog(result)">
                        <div class="font-semibold">{{ result.accession_number }} - {{ result.title }}</div>
                        <div class="text-xs text-gray-500">{{ result.book?.authors }} | {{ result.book?.publication_year }}</div>
                    </div>
                </div>
            </div>
        </div>
        <WelcomeBookDialog v-if="showDialog" :record="selectedRecord" @close="showDialog = false" />
    </div>
</template>
