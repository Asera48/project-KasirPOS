<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Point of Sale') }}
        </h2>
    </x-slot>

    <div 
        x-data="pos({{ json_encode($products) }}, {{ json_encode($members) }}, {{ json_encode($paymentMethods) }})"
        class="flex flex-col lg:flex-row lg:h-[calc(100vh-8.5rem)] max-w-full mx-auto"
    >
        <!-- Kolom Kiri: Daftar Produk -->
        <div class="w-full lg:w-3/5 p-4 lg:overflow-y-auto">
            <!-- Pencarian Produk -->
            <div class="mb-4 relative">
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    @input.debounce.300ms="searchProducts()"
                    placeholder="Cari produk berdasarkan nama atau SKU..." 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500"
                >
                <div x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2" style="display: none;">
                    <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>

            <!-- Grid Produk -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <template x-for="product in products" :key="product.id">
                    <div @click="addToCart(product)" class="bg-white dark:bg-gray-800 rounded-lg shadow-md cursor-pointer hover:shadow-xl transition-shadow duration-200 relative overflow-hidden">
                        <template x-if="product.discount && product.discount.id"><div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10">DISKON</div></template>
                        <div class="p-4">
                            <img :src="`https://placehold.co/300x200/e2e8f0/333?text=${encodeURIComponent(product.name)}`" :alt="product.name" class="w-full h-24 object-cover rounded-md mb-3">
                            <h4 class="font-semibold text-sm text-gray-800 dark:text-gray-200 truncate" :title="product.name" x-text="product.name"></h4>
                            <template x-if="product.discount && product.discount.id">
                                <div>
                                    <p class="text-lg font-bold text-red-500 dark:text-red-400" x-text="calculateDiscountedPrice(product)"></p>
                                    <p class="text-sm text-gray-500 line-through" x-text="format_rupiah(product.price)"></p>
                                </div>
                            </template>
                            <template x-if="!product.discount || !product.discount.id"><p class="text-lg font-bold text-indigo-600 dark:text-indigo-400" x-text="format_rupiah(product.price)"></p></template>
                        </div>
                        <div class="w-full bg-indigo-500 text-white text-sm font-bold py-2 rounded-b-lg text-center">Tambah</div>
                    </div>
                </template>
                <template x-if="!loading && products.length === 0 && searchQuery.length > 1"><p class="col-span-full text-center text-gray-500">Produk tidak ditemukan.</p></template>
            </div>
        </div>

        <!-- Kolom Kanan: Keranjang & Pembayaran -->
        <div class="w-full lg:w-2/5 bg-white dark:bg-gray-800 p-6 flex flex-col shadow-lg lg:shadow-2xl lg:h-full">
            <form x-ref="checkoutForm" action="{{ route('kasir.transactions.store') }}" method="POST" class="flex flex-col h-full">
                @csrf
                <div class="flex-1 min-h-0 overflow-y-auto pr-4 -mr-4">
                    <!-- Pemilihan Member -->
                    <div class="mb-4">
                        <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pelanggan (Opsional)</label>
                        <select name="member_id" id="member_id" x-model="selectedMemberId" @change="updateMemberDetails()" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Umum</option>
                            <template x-for="member in members" :key="member.id">
                                <option :value="member.id" x-text="member.name"></option>
                            </template>
                        </select>
                        <div x-show="selectedMember" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Poin tersedia: <span x-text="selectedMember.points" class="font-bold"></span>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 border-b pb-4 border-gray-200 dark:border-gray-700">Keranjang</h3>
                    <div class="my-4">
                        <div class="space-y-4">
                            <template x-for="item in cart" :key="item.id">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-gray-200" x-text="item.name"></p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="`${item.quantity} x ${format_rupiah(item.final_price)}`"></p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="number" :value="item.quantity" @input.debounce.500ms="updateQuantity(item.id, $event.target.value)" class="w-16 text-center border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        <button type="button" @click="removeFromCart(item.id)" class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </div>
                                </div>
                            </template>
                            <template x-if="cart.length === 0"><p class="text-center text-gray-500 dark:text-gray-400">Keranjang masih kosong.</p></template>
                        </div>
                    </div>

                    <!-- Rincian & Pembayaran -->
                    <div class="border-t pt-4 border-gray-200 dark:border-gray-700 space-y-3">
                        <div class="flex justify-between text-gray-600 dark:text-gray-400"><span>Subtotal</span><span x-text="format_rupiah(subtotal)"></span></div>
                        <div x-show="pointDiscount > 0" class="flex justify-between text-red-600 dark:text-red-400">
                            <span>Diskon Poin</span>
                            <span x-text="'-' + format_rupiah(pointDiscount)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>Pajak (<span x-text="taxRate"></span>%)</span>
                            <span x-text="format_rupiah(tax)"></span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-gray-900 dark:text-white"><span>Total</span><span x-text="format_rupiah(total)"></span></div>
                        
                        <div x-show="selectedMember" class="pt-2">
                            <label for="redeem_points" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gunakan Poin</label>
                            <div class="mt-1 flex gap-2">
                                <input type="number" id="redeem_points" x-model.number="pointsToRedeem" placeholder="0" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <button type="button" @click="applyPointDiscount()" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Terapkan</button>
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-2">
                                <template x-for="method in paymentMethods" :key="method.id">
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" :value="method.code" x-model="paymentMethod" class="h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300" x-text="method.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div x-show="paymentMethod === 'cash'" class="pt-2">
                            <label for="amount_paid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Uang Diterima</label>
                            <input type="number" name="paid" x-model.number="amountPaid" id="amount_paid" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        </div>
                        <div x-show="paymentMethod === 'cash' && amountPaid >= total" class="flex justify-between text-lg font-medium text-gray-800 dark:text-gray-200"><span>Kembalian</span><span x-text="format_rupiah(amountPaid - total)"></span></div>
                    </div>
                </div>
                
                <!-- Hidden inputs -->
                <template x-for="(item, index) in cart">
                    <div>
                        <input type="hidden" :name="`items[${index}][product_id]`" :value="item.id">
                        <input type="hidden" :name="`items[${index}][quantity]`" :value="item.quantity">
                    </div>
                </template>
                <input type="hidden" name="redeemed_points" :value="redeemedPoints">

                <div class="mt-6 flex-shrink-0">
                    <button type="submit" @click.prevent="checkout()" class="w-full bg-green-500 text-white font-bold py-3 rounded-lg text-lg hover:bg-green-600 transition-colors duration-200">Bayar</button>
                </div>
            </form>
        </div>

        <div x-show="showQrModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;">
            <div @click.away="showQrModal = false" class="bg-white dark:bg-gray-800 rounded-lg p-8 text-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Pindai QR Code</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Total Pembayaran: <span class="font-bold" x-text="format_rupiah(total)"></span></p>
                <img :src="qrImageUrl" alt="QR Code" class="w-64 h-64 mx-auto mb-6 border-4 border-gray-300 dark:border-gray-600 rounded-lg">
                <button @click="confirmQrPayment()" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">
                    Konfirmasi Pembayaran Berhasil
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function format_rupiah(number) {
            if (isNaN(parseFloat(number))) return 'Rp0';
            return 'Rp' + new Intl.NumberFormat('id-ID').format(number);
        }

        function pos(initialProducts, members, paymentMethods) {
            return {
                products: initialProducts, 
                cart: [],
                searchQuery: '',
                loading: false,
                members: members,
                paymentMethods: paymentMethods,
                paymentMethod: paymentMethods.length > 0 ? paymentMethods[0].code : 'cash',
                amountPaid: 0, 
                taxRate: {{ setting('tax_rate', 11) }},
                selectedMemberId: '',
                selectedMember: null,
                pointsToRedeem: 0,
                redeemedPoints: 0,
                pointValue: {{ setting('point_value', 100) }},
                showQrModal: false,
                qrImageUrl: '',

                updateMemberDetails() {
                    this.selectedMember = this.members.find(m => m.id == this.selectedMemberId) || null;
                    this.pointsToRedeem = 0;
                    this.redeemedPoints = 0;
                },

                applyPointDiscount() {
                    if (!this.selectedMember) {
                        alert('Pilih member terlebih dahulu.');
                        return;
                    }
                    if (this.pointsToRedeem > this.selectedMember.points) {
                        alert('Poin yang ingin ditukar melebihi poin yang dimiliki member.');
                        return;
                    }
                    const maxRedeemValue = this.subtotal;
                    const maxRedeemPoints = Math.floor(maxRedeemValue / this.pointValue);
                    if (this.pointsToRedeem > maxRedeemPoints) {
                        alert(`Maksimal poin yang bisa ditukar untuk transaksi ini adalah ${maxRedeemPoints} poin.`);
                        this.pointsToRedeem = maxRedeemPoints;
                        return;
                    }
                    this.redeemedPoints = this.pointsToRedeem;
                },

                get pointDiscount() {
                    return this.redeemedPoints * this.pointValue;
                },

                searchProducts() {
                    if (this.searchQuery.length < 2) {
                        this.products = initialProducts;
                        return;
                    }
                    this.loading = true;
                    fetch(`{{ route('api.products.search') }}?q=${this.searchQuery}`, {
                        headers: { 
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.ok ? response.json() : Promise.reject(response))
                    .then(data => { this.products = data; })
                    .catch(error => console.error('Fetch error:', error))
                    .finally(() => this.loading = false);
                },
                
                calculateDiscountedPrice(product) {
                    if (!product.discount) {
                        return format_rupiah(product.price);
                    }
                    let discountedPrice;
                    if (product.discount.type === 'percentage') {
                        discountedPrice = product.price - (product.price * product.discount.value / 100);
                    } else {
                        discountedPrice = product.price - product.discount.value;
                    }
                    return format_rupiah(Math.max(0, discountedPrice));
                },
                
                // PERBAIKAN: Membuat objek keranjang secara manual
                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.quantity++;
                        return;
                    }
                    
                    let finalPrice = product.price;
                    if (product.discount && product.discount.id) {
                        if (product.discount.type === 'percentage') {
                            finalPrice = product.price - (product.price * product.discount.value / 100);
                        } else {
                            finalPrice = product.price - product.discount.value;
                        }
                    }

                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        discount: product.discount,
                        quantity: 1,
                        final_price: Math.max(0, finalPrice)
                    });
                },

                removeFromCart(productId) {
                    this.cart = this.cart.filter(item => item.id !== productId);
                },

                updateQuantity(productId, newQuantity) {
                    const item = this.cart.find(item => item.id === productId);
                    if (item) {
                        if (newQuantity > 0) {
                            item.quantity = parseInt(newQuantity);
                        } else {
                            this.removeFromCart(productId);
                        }
                    }
                },

                get subtotal() {
                    return this.cart.reduce((acc, item) => acc + (item.final_price * item.quantity), 0);
                },
                get subtotalAfterDiscount() {
                    return this.subtotal - this.pointDiscount;
                },
                get tax() { 
                    return this.subtotalAfterDiscount * (this.taxRate / 100); 
                },
                get total() { 
                    const unroundedTotal = this.subtotalAfterDiscount + this.tax;
                    if (this.paymentMethod === 'cash') {
                        return Math.round(unroundedTotal / 100) * 100;
                    }
                    return unroundedTotal;
                },
                
                checkout() {
                    if (this.cart.length === 0) {
                        alert('Keranjang masih kosong!');
                        return;
                    }

                    const selectedMethod = this.paymentMethods.find(m => m.code === this.paymentMethod);
                    if (selectedMethod && selectedMethod.qr_code_image) {
                        this.qrImageUrl = `/storage/${selectedMethod.qr_code_image}`;
                        this.showQrModal = true;
                        return;
                    }

                    if (this.paymentMethod === 'cash' && this.amountPaid < this.total) {
                        alert('Jumlah uang yang dibayarkan kurang dari total belanja.');
                        return;
                    }
                    
                    if (this.paymentMethod !== 'cash') {
                        this.amountPaid = this.total;
                    }
                    
                    this.$nextTick(() => {
                        this.$refs.checkoutForm.submit();
                    });
                },

                confirmQrPayment() {
                    this.showQrModal = false;
                    this.amountPaid = this.total;
                    this.$nextTick(() => {
                        this.$refs.checkoutForm.submit();
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
