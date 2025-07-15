<div class="w-full lg:w-2/5 bg-white dark:bg-gray-800 p-6 flex flex-col shadow-lg lg:shadow-2xl">
    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 border-b pb-4 border-gray-200 dark:border-gray-700">Keranjang</h3>
    
    <!-- Daftar Item di Keranjang -->
    <div class="flex-grow overflow-y-auto my-4 pr-2 -mr-2">
        <div class="space-y-4">
            <template x-for="item in cart" :key="item.id">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-200" x-text="item.name"></p>
                        <p class="text-sm text-gray-500" x-text="format_rupiah(item.price)"></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <input 
                            type="number" 
                            :value="item.quantity"
                            @input.debounce.500ms="updateQuantity(item.id, $event.target.value)"
                            class="w-16 text-center border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                        >
                        <button @click="removeFromCart(item.id)" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </template>
            <template x-if="cart.length === 0">
                <p class="text-center text-gray-500">Keranjang masih kosong.</p>
            </template>
        </div>
    </div>

    <!-- Rincian Total -->
    <div class="border-t pt-4 border-gray-200 dark:border-gray-700 space-y-2">
        <div class="flex justify-between text-gray-600 dark:text-gray-300">
            <span>Subtotal</span>
            <span x-text="format_rupiah(subtotal)"></span>
        </div>
        <div class="flex justify-between text-gray-600 dark:text-gray-300">
            <span>Pajak (11%)</span>
            <span x-text="format_rupiah(tax)"></span>
        </div>
        <div class="flex justify-between text-xl font-bold text-gray-900 dark:text-white">
            <span>Total</span>
            <span x-text="format_rupiah(total)"></span>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-6">
        <button @click="checkout" class="w-full bg-green-500 text-white font-bold py-3 rounded-lg text-lg hover:bg-green-600 transition-colors duration-200">
            Bayar
        </button>
    </div>
</div>
