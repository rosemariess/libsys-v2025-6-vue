<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { ref, computed } from 'vue';
import { Input } from '@/components/ui/input';
import { Search } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
  {
      title: 'Borrowings',
      href: '/borrowings',
  },
];

defineProps({
    transactions: Object,
})

// Dummy data for demonstration if no backend data
const dummyTransactions = [
    {
        id: 1,
        borrower_name: 'Juan Dela Cruz',
        borrower_id: '20231234',
        book_title: 'Sample Book',
        transaction_date: '2025-08-01',
        borrow_date: '2025-08-01',
        must_return_date: '2025-08-10',
        returned_at: '2025-08-15',
    },
    {
        id: 2,
        borrower_name: 'Maria Santos',
        borrower_id: '20231235',
        book_title: 'Another Book',
        transaction_date: '2025-08-02',
        borrow_date: '2025-08-02',
        must_return_date: '2025-08-12',
        returned_at: null,
    },
    {
        id: 3,
        borrower_name: 'Pedro Reyes',
        borrower_id: '20231236',
        book_title: 'Third Book',
        transaction_date: '2025-08-03',
        borrow_date: '2025-08-03',
        must_return_date: '2025-08-13',
        returned_at: '2025-08-14',
    },
];

const transactionsList = computed(() => {
    // Use backend data if available, otherwise dummy
    const data = (typeof transactions !== 'undefined' && transactions?.data?.length) ? transactions.data : dummyTransactions;
    if (activeTab.value === 'done') {
        return data.filter(t => t.returned_at);
    }
    if (activeTab.value === 'latest') {
        return data.slice(0, 5);
    }
    return data;
});

const activeTab = ref('latest');
const showReturnModal = ref(false);
const selectedTransaction = ref(null);
const showActionMenu = ref(null);
const showScanModal = ref(false);
const searchQuery = ref('');

function openReturnModal(transaction) {
    selectedTransaction.value = transaction;
    showReturnModal.value = true;
}
function closeReturnModal() {
    showReturnModal.value = false;
    selectedTransaction.value = null;
}
function openActionMenu(id) {
    showActionMenu.value = id;
}
function closeActionMenu() {
    showActionMenu.value = null;
}
function openScanModal() {
    showScanModal.value = true;
    setTimeout(() => {
        const input = document.getElementById('studentSearchInput');
        if (input) input.focus();
    }, 300);
}
function closeScanModal() {
    showScanModal.value = false;
}

// Dummy delay/fine calculation for modal
function getDelayDays(transaction) {
    // Replace with real logic if backend available
    return 3;
}
function getFine(transaction) {
    // Replace with real logic if backend available
    return 50;
}
function confirmReturn() {
    // Backend-friendly: emit event or call API to mark as returned
    // For demo, just remove from dummyTransactions and update UI
    if (selectedTransaction.value) {
        // If backend, replace with API call and reload data
        const idx = dummyTransactions.findIndex(t => t.id === selectedTransaction.value.id);
        if (idx !== -1) {
            dummyTransactions[idx].returned_at = new Date().toISOString().slice(0, 10); // Mark as returned today
        }
        // Optionally, you could remove from dummyTransactions if you want to hide from latest/all
        // dummyTransactions.splice(idx, 1);
    }
    closeReturnModal();
    activeTab.value = 'done'; // Go to Done Transactions tab
}

</script>

<template>
    <Head title="Latest Transactions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-2">
                <div class="flex gap-2 items-center w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center justify-center z-10 pointer-events-none">
                            <Search class="size-5 text-muted-foreground" />
                        </span>
                        <Input id="studentSearchInput" v-model="searchQuery" placeholder="Search or scan student ID..." class="w-full pl-10" />
                    </div>
                    <Button type="button" variant="ghost" class="ml-2" title="Scan Barcode or RFID" @click="openScanModal()">
                        <i class="fas fa-barcode text-lg"></i>
                        <i class="fas fa-id-card text-lg ml-1"></i>
                    </Button>
                </div>
                <div class="flex gap-2 items-center">
                    <Link :href="route('borrowings.create')">
                        <Button variant="secondary">Borrow Book</Button>
                    </Link>
                </div>
            </div>
            <div class="flex gap-2 border-b mb-4">
                <button :class="['px-4 py-2', activeTab==='latest' ? 'border-b-2 border-usepmaroon font-bold' : 'text-gray-500']" @click="activeTab='latest'">Latest Transactions</button>
                <button :class="['px-4 py-2', activeTab==='all' ? 'border-b-2 border-usepmaroon font-bold' : 'text-gray-500']" @click="activeTab='all'">All Transactions</button>
                <button :class="['px-4 py-2', activeTab==='done' ? 'border-b-2 border-usepmaroon font-bold' : 'text-gray-500']" @click="activeTab='done'">Done Transactions</button>
            </div>
            <div v-if="activeTab==='latest'">
                <h1 class="text-2xl mb-2">Latest transactions</h1>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded shadow text-sm">
                        <thead>
                            <tr class="bg-usepmaroon text-white">
                                <th class="p-3 text-left">Borrower Name</th>
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Book</th>
                                <th class="p-3 text-left">Date of Transaction</th>
                                <th class="p-3 text-left">Borrow Date</th>
                                <th class="p-3 text-left">Must Be Returned</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="transaction in transactionsList" :key="transaction.id" class="border-b hover:bg-usepmaroon/10">
                                <td class="p-3">{{ transaction.borrower_name }}</td>
                                <td class="p-3">{{ transaction.borrower_id }}</td>
                                <td class="p-3">{{ transaction.book_title }}</td>
                                <td class="p-3">{{ transaction.transaction_date }}</td>
                                <td class="p-3">{{ transaction.borrow_date }}</td>
                                <td class="p-3">{{ transaction.must_return_date }}</td>
                                <td class="p-3 text-center relative">
                                    <Button variant="ghost" class="text-usepmaroon hover:bg-usepmaroon/10" @click="openActionMenu(transaction.id)">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </Button>
                                    <div v-if="showActionMenu === transaction.id" class="absolute right-0 mt-2 w-32 bg-white border rounded shadow z-10">
                                        <button class="w-full text-left px-4 py-2 hover:bg-usepmaroon/10 text-usepmaroon" @click="openReturnModal(transaction); closeActionMenu()">Return Book</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-else-if="activeTab==='all'">
                <h1 class="text-2xl mb-2">All transactions</h1>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded shadow text-sm">
                        <thead>
                            <tr class="bg-usepmaroon text-white">
                                <th class="p-3 text-left">Borrower Name</th>
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Book</th>
                                <th class="p-3 text-left">Date of Transaction</th>
                                <th class="p-3 text-left">Borrow Date</th>
                                <th class="p-3 text-left">Must Be Returned</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="transaction in transactionsList" :key="transaction.id" class="border-b hover:bg-usepmaroon/10">
                                <td class="p-3">{{ transaction.borrower_name }}</td>
                                <td class="p-3">{{ transaction.borrower_id }}</td>
                                <td class="p-3">{{ transaction.book_title }}</td>
                                <td class="p-3">{{ transaction.transaction_date }}</td>
                                <td class="p-3">{{ transaction.borrow_date }}</td>
                                <td class="p-3">{{ transaction.must_return_date }}</td>
                                <td class="p-3 text-center relative">
                                    <Button variant="ghost" class="text-usepmaroon hover:bg-usepmaroon/10" @click="openActionMenu(transaction.id)">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </Button>
                                    <div v-if="showActionMenu === transaction.id" class="absolute right-0 mt-2 w-32 bg-white border rounded shadow z-10">
                                        <button class="w-full text-left px-4 py-2 hover:bg-usepmaroon/10 text-usepmaroon" @click="openReturnModal(transaction); closeActionMenu()">Return Book</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-else-if="activeTab==='done'">
                <h1 class="text-2xl mb-2">Done Transactions</h1>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded shadow text-sm">
                        <thead>
                            <tr class="bg-usepmaroon text-white">
                                <th class="p-3 text-left">Borrower Name</th>
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Book</th>
                                <th class="p-3 text-left">Date of Transaction</th>
                                <th class="p-3 text-left">Borrow Date</th>
                                <th class="p-3 text-left">Must Be Returned</th>
                                <th class="p-3 text-left">Returned At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="transaction in transactionsList" :key="transaction.id" class="border-b hover:bg-usepmaroon/10">
                                <td class="p-3">{{ transaction.borrower_name }}</td>
                                <td class="p-3">{{ transaction.borrower_id }}</td>
                                <td class="p-3">{{ transaction.book_title }}</td>
                                <td class="p-3">{{ transaction.transaction_date }}</td>
                                <td class="p-3">{{ transaction.borrow_date }}</td>
                                <td class="p-3">{{ transaction.must_return_date }}</td>
                                <td class="p-3">{{ transaction.returned_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Return Modal -->
        <Dialog v-model:open="showReturnModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <DialogOverlay class="absolute inset-0 bg-black/40" />
                <DialogContent class="relative max-w-sm w-full mx-auto text-center bg-white rounded-lg shadow-lg flex items-center justify-center min-h-[40vh]">
                    <div class="py-6 w-full">
                        <div class="flex flex-col items-center mb-2">
                            <i class="fas fa-undo-alt text-5xl text-usepmaroon mb-2"></i>
                        </div>
                        <h2 class="text-xl font-bold mb-2">Return Book</h2>
                        <p class="text-gray-600 mb-2">Do you want to return the book?</p>
                        <p class="text-gray-600 mb-2">The book is delayed by <span class="font-bold">{{ getDelayDays(selectedTransaction) }}</span> days.</p>
                        <p class="text-gray-600 mb-4">Fine: <span class="font-bold text-red-600">₱{{ getFine(selectedTransaction) }}</span></p>
                        <div class="flex gap-2 mt-4 justify-center">
                            <Button variant="outline" @click="closeReturnModal">Cancel</Button>
                            <Button class="bg-usepmaroon text-white hover:bg-usepmaroon/90" @click="confirmReturn">Confirm Return</Button>
                        </div>
                    </div>
                </DialogContent>
            </div>
        </Dialog>
        <!-- Scan Modal -->
        <Dialog v-model:open="showScanModal">
            <DialogOverlay />
            <DialogContent class="max-w-sm mx-auto text-center">
                <div class="py-6 flex flex-col items-center">
                    <div class="flex flex-row items-center gap-4 mb-2">
                        <i class="fas fa-barcode text-5xl text-gray-700"></i>
                        <i class="fas fa-id-card text-5xl text-gray-700"></i>
                    </div>
                    <span class="text-xs text-gray-500 mb-2">Barcode or RFID Scanner</span>
                    <h2 class="text-xl font-bold mb-2">Scan Barcode or RFID</h2>
                    <p class="text-gray-600 mb-4">You can scan now. Please present the barcode or RFID card to the scanner. The input will be automatically filled.</p>
                    <Button @click="closeScanModal" class="mt-2 w-full">Close</Button>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
