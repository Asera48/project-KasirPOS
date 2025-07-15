<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Discount;
use App\Models\Member;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Supplier;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UserSeeder::class);

        $makananMinuman = Category::create(['name' => 'Makanan & Minuman']);
        $kebersihan = Category::create(['name' => 'Kebersihan & Perawatan Tubuh']);
        $atk = Category::create(['name' => 'Alat Tulis Kantor']);

        // 3. Membuat Supplier
        Supplier::create(['name' => 'PT Indofood CBP Sukses Makmur Tbk', 'contact_person' => 'Budi', 'phone' => '021-57958822']);
        Supplier::create(['name' => 'PT Unilever Indonesia Tbk', 'contact_person' => 'Ani', 'phone' => '021-80827000']);
        Supplier::create(['name' => 'PT Pabrik Kertas Tjiwi Kimia Tbk', 'contact_person' => 'Citra', 'phone' => '021-3928383']);

        // 4. Membuat Produk (15 Produk)
        $p1 = Product::create(['name' => 'Indomie Mi Goreng Original', 'category_id' => $makananMinuman->id, 'price' => 3000, 'cost_price' => 2500, 'stock' => 250, 'barcode' => '8992761134567']);
        Product::create(['name' => 'Teh Botol Sosro Kotak 250ml', 'category_id' => $makananMinuman->id, 'price' => 3500, 'cost_price' => 2800, 'stock' => 150, 'barcode' => '8998866110014']);
        $p3 = Product::create(['name' => 'Chitato Sapi Panggang 68g', 'category_id' => $makananMinuman->id, 'price' => 11000, 'cost_price' => 9500, 'stock' => 80, 'barcode' => '8992761123458']);
        Product::create(['name' => 'Aqua Air Mineral 600ml', 'category_id' => $makananMinuman->id, 'price' => 3000, 'cost_price' => 2200, 'stock' => 200, 'barcode' => '8992761198765']);
        Product::create(['name' => 'Oreo Original 133g', 'category_id' => $makananMinuman->id, 'price' => 10000, 'cost_price' => 8500, 'stock' => 90, 'barcode' => '7622210623942']);

        Product::create(['name' => 'Sabun Lifebuoy Total 10 85g', 'category_id' => $kebersihan->id, 'price' => 4500, 'cost_price' => 3800, 'stock' => 120, 'barcode' => '8999999001234']);
        Product::create(['name' => 'Pepsodent Pencegah Gigi Berlubang 190g', 'category_id' => $kebersihan->id, 'price' => 14000, 'cost_price' => 11500, 'stock' => 95, 'barcode' => '8999999045678']);
        $p8 = Product::create(['name' => 'Shampo Clear Men Cool Sport 160ml', 'category_id' => $kebersihan->id, 'price' => 28000, 'cost_price' => 24000, 'stock' => 70, 'barcode' => '8999999112233']);
        Product::create(['name' => 'Rexona Men Ice Cool Roll On 45ml', 'category_id' => $kebersihan->id, 'price' => 18000, 'cost_price' => 15000, 'stock' => 65, 'barcode' => '8999999532187']);
        Product::create(['name' => 'Sunlight Cairan Pencuci Piring 755ml', 'category_id' => $kebersihan->id, 'price' => 15000, 'cost_price' => 12500, 'stock' => 110, 'barcode' => '8999999038755']);

        Product::create(['name' => 'Pulpen Standard AE7 Hitam', 'category_id' => $atk->id, 'price' => 2500, 'cost_price' => 1800, 'stock' => 300, 'barcode' => '6947602812345']);
        Product::create(['name' => 'Buku Tulis Sinar Dunia 38 Lbr', 'category_id' => $atk->id, 'price' => 4000, 'cost_price' => 3200, 'stock' => 180, 'barcode' => '8991389005678']);
        Product::create(['name' => 'Tipe-X / Correction Pen Kenko', 'category_id' => $atk->id, 'price' => 8000, 'cost_price' => 6500, 'stock' => 60, 'barcode' => '8993988012345']);
        Product::create(['name' => 'Kertas HVS A4 70gsm Sinar Dunia', 'category_id' => $atk->id, 'price' => 55000, 'cost_price' => 48000, 'stock' => 40, 'barcode' => '8991389000018']);
        Product::create(['name' => 'Spidol Snowman Board Marker Hitam', 'category_id' => $atk->id, 'price' => 9000, 'cost_price' => 7000, 'stock' => 85, 'barcode' => '8993888110017']);
        
        // 5. Membuat Diskon
        Discount::create(['product_id' => $p3->id, 'type' => 'percentage', 'value' => 10, 'start_date' => Carbon::now()->subDays(3), 'end_date' => Carbon::now()->addDays(7)]);
        Discount::create(['product_id' => $p8->id, 'type' => 'fixed', 'value' => 5000, 'start_date' => Carbon::now()->subDays(1), 'end_date' => Carbon::now()->addDays(2)]);
        Discount::create(['product_id' => $p1->id, 'type' => 'percentage', 'value' => 15, 'start_date' => Carbon::now()->addDays(5), 'end_date' => Carbon::now()->addDays(10)]);

        // 6. Membuat Member
        Member::create(['name' => 'Budi Santoso', 'phone' => '081234567890', 'points' => 50]);
        Member::create(['name' => 'Citra Lestari', 'phone' => '081234567891', 'points' => 120]);
        Member::create(['name' => 'Asep Sunandar', 'phone' => '081234567892', 'points' => 0]);

        // 7. Membuat Metode Pembayaran
        PaymentMethod::create(['name' => 'Tunai', 'code' => 'cash', 'is_active' => true]);
        PaymentMethod::create(['name' => 'Kartu Debit', 'code' => 'card', 'is_active' => true]);
        PaymentMethod::create(['name' => 'QRIS', 'code' => 'qris', 'is_active' => true]);

        // 8. Membuat Pengaturan Default
        Setting::create(['key' => 'app_name', 'value' => 'TokoKu']);
        Setting::create(['key' => 'store_address', 'value' => 'Jl. Merdeka No. 123, Jakarta Pusat']);
        Setting::create(['key' => 'store_phone', 'value' => '021-987-6543']);
        Setting::create(['key' => 'tax_rate', 'value' => '11']);
        Setting::create(['key' => 'points_per_amount', 'value' => '10000']);
        Setting::create(['key' => 'point_value', 'value' => '100']);
    }
}
