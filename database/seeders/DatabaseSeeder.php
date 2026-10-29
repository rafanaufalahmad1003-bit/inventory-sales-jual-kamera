<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks for clean seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sale_items')->truncate();
        DB::table('sales')->truncate();
        DB::table('purchase_items')->truncate();
        DB::table('purchases')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('customers')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. SEED USERS
        $owner = User::create([
            'name' => 'Budi Santoso (Owner)',
            'email' => 'owner@camera.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_OWNER,
            'phone' => '08111222333',
            'address' => 'Jl. Kamera Raya No. 1, Jakarta Pusat',
        ]);

        $admin = User::create([
            'name' => 'Ahmad Fauzi (Admin)',
            'email' => 'admin@camera.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'phone' => '08114445556',
            'address' => 'Jl. Lensa Indah No. 12, Jakarta Selatan',
        ]);

        $staff = User::create([
            'name' => 'Siti Aminah (Staff Toko)',
            'email' => 'staff@camera.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STAFF,
            'phone' => '08117778889',
            'address' => 'Jl. Blitz Fotografi No. 45, Jakarta Barat',
        ]);

        $pelanggan = User::create([
            'name' => 'Reza Rahardian (Pelanggan)',
            'email' => 'pelanggan@camera.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PELANGGAN,
            'phone' => '081345678901',
            'address' => 'Jl. Sudirman No. 88, Jakarta Selatan',
        ]);

        // 2. SEED CATEGORIES
        $catMirrorless = Category::create([
            'name' => 'Kamera Mirrorless',
            'description' => 'Kamera digital tanpa cermin dengan sensor canggih untuk profesional & hobi.',
        ]);

        $catDslr = Category::create([
            'name' => 'Kamera DSLR',
            'description' => 'Kamera Digital Single Lens Reflex dengan daya tahan baterai tinggi dan optical viewfinder.',
        ]);

        $catAction = Category::create([
            'name' => 'Action & Pocket Cam',
            'description' => 'Kamera ringkas untuk vlogging, olahraga ekstrem, dan traveling.',
        ]);

        $catLens = Category::create([
            'name' => 'Lensa Kamera',
            'description' => 'Lensa prime, zoom, dan makro untuk berbagai mounting kamera ternama.',
        ]);

        $catAcc = Category::create([
            'name' => 'Aksesoris Fotografi',
            'description' => 'Tripod, memory card, flash speedlite, tas kamera, dan baterai cadangan.',
        ]);

        // 3. SEED SUPPLIERS
        $supCanon = Supplier::create([
            'name' => 'PT Canon Marketing Indonesia',
            'contact_person' => 'Hendra Setiawan',
            'phone' => '021-29226000',
            'email' => 'supplier@canon.co.id',
            'address' => 'Kawasan Niaga Kelapa Gading, Jakarta Utara',
        ]);

        $supSony = Supplier::create([
            'name' => 'PT Sony Retail Indonesia',
            'contact_person' => 'Dewi Lestari',
            'phone' => '021-5730800',
            'email' => 'b2b@sony.co.id',
            'address' => 'Wisma Sony, TB Simatupang, Jakarta Selatan',
        ]);

        $supFuji = Supplier::create([
            'name' => 'PT Fujifilm Distribusi Indonesia',
            'contact_person' => 'Bambang Irawan',
            'phone' => '021-52963388',
            'email' => 'sales@fujifilm.co.id',
            'address' => 'Jl. Gatot Subroto Kav. 38, Jakarta Selatan',
        ]);

        // 4. SEED CUSTOMERS
        $cust1 = Customer::create([
            'name' => 'Reza Rahardian',
            'phone' => '081345678901',
            'email' => 'pelanggan@camera.com',
            'address' => 'Jl. Sudirman No. 88, Jakarta Selatan',
        ]);

        $cust2 = Customer::create([
            'name' => 'Studio Foto Mentari',
            'phone' => '081234567890',
            'email' => 'mentari.photo@gmail.com',
            'address' => 'Jl. Riau No. 15, Bandung',
        ]);

        $cust3 = Customer::create([
            'name' => 'CV Visual Kreasi Film',
            'phone' => '081987654321',
            'email' => 'production@visualkreasi.com',
            'address' => 'Jl. Pemuda No. 70, Surabaya',
        ]);

        $cust4 = Customer::create([
            'name' => 'Dian Sastro Photo Works',
            'phone' => '082123456789',
            'email' => 'diansastro@works.id',
            'address' => 'Ubud Sunset Road No. 10, Bali',
        ]);

        // 5. SEED PRODUCTS
        $p1 = Product::create([
            'category_id' => $catMirrorless->id,
            'sku' => 'CAM-MIR-001',
            'name' => 'Canon EOS R6 Mark II Body',
            'description' => 'Sensor full-frame 24.2 MP, 40 fps continuous shooting, 4K 60p oversampled video.',
            'purchase_price' => 32000000,
            'selling_price' => 37500000,
            'stock' => 12,
            'minimum_stock' => 3,
            'image' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=500&auto=format&fit=crop&q=60',
        ]);

        $p2 = Product::create([
            'category_id' => $catMirrorless->id,
            'sku' => 'CAM-MIR-002',
            'name' => 'Sony Alpha 7 IV (A7 IV) Kit 28-70mm',
            'description' => 'Sensor 33 MP BSI Exmor R, real-time autofocus eye-tracking, 4K video recording.',
            'purchase_price' => 31000000,
            'selling_price' => 36500000,
            'stock' => 8,
            'minimum_stock' => 3,
            'image' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=500&auto=format&fit=crop&q=60',
        ]);

        $p3 = Product::create([
            'category_id' => $catMirrorless->id,
            'sku' => 'CAM-MIR-003',
            'name' => 'Fujifilm X-T5 Body Silver',
            'description' => 'Sensor 40.2 MP X-Trans CMOS 5 HR, Film Simulation legendaris, 5-axis IBIS hingga 7.0 stops.',
            'purchase_price' => 24000000,
            'selling_price' => 28500000,
            'stock' => 6,
            'minimum_stock' => 2,
            'image' => 'https://images.unsplash.com/photo-1581591524425-c7e0978865fc?w=500&auto=format&fit=crop&q=60',
        ]);

        $p4 = Product::create([
            'category_id' => $catDslr->id,
            'sku' => 'CAM-DSL-001',
            'name' => 'Canon EOS 90D Body',
            'description' => 'Sensor APS-C 32.5 MP, Dual Pixel CMOS AF, baterai tahan hingga 1300 jepretan.',
            'purchase_price' => 17000000,
            'selling_price' => 20500000,
            'stock' => 5,
            'minimum_stock' => 2,
            'image' => 'https://images.unsplash.com/photo-1606999122070-2629b4e4b95f?w=500&auto=format&fit=crop&q=60',
        ]);

        $p5 = Product::create([
            'category_id' => $catDslr->id,
            'sku' => 'CAM-DSL-002',
            'name' => 'Nikon D780 Body',
            'description' => 'Kamera DSLR full-frame tangguh dengan 51-point AF system dan low-light ISO luar biasa.',
            'purchase_price' => 26000000,
            'selling_price' => 30000000,
            'stock' => 2, // Low stock on purpose
            'minimum_stock' => 3,
            'image' => 'https://images.unsplash.com/photo-1512790182412-b19e6d62bc39?w=500&auto=format&fit=crop&q=60',
        ]);

        $p6 = Product::create([
            'category_id' => $catAction->id,
            'sku' => 'CAM-ACT-001',
            'name' => 'GoPro Hero 12 Black Creator Edition',
            'description' => 'Video 5.3K 60fps, stabilisasi HyperSmooth 6.0, water resistant 10 meter tanpa case.',
            'purchase_price' => 6500000,
            'selling_price' => 8200000,
            'stock' => 15,
            'minimum_stock' => 5,
            'image' => 'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=500&auto=format&fit=crop&q=60',
        ]);

        $p7 = Product::create([
            'category_id' => $catAction->id,
            'sku' => 'CAM-ACT-002',
            'name' => 'DJI Osmo Pocket 3 Creator Combo',
            'description' => 'Sensor 1-inch CMOS, video 4K 120fps, layar putar OLED 2-inch dan 3-axis mechanical gimbal.',
            'purchase_price' => 7800000,
            'selling_price' => 9600000,
            'stock' => 3, // Low stock on purpose
            'minimum_stock' => 4,
            'image' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=500&auto=format&fit=crop&q=60',
        ]);

        $p8 = Product::create([
            'category_id' => $catLens->id,
            'sku' => 'LEN-CAN-001',
            'name' => 'Canon RF 24-70mm f/2.8 L IS USM',
            'description' => 'Lensa zoom standar profesional dengan aperture konstan f/2.8 dan image stabilizer.',
            'purchase_price' => 30000000,
            'selling_price' => 35000000,
            'stock' => 7,
            'minimum_stock' => 2,
            'image' => 'https://images.unsplash.com/photo-1617005082133-548c4dd27f35?w=500&auto=format&fit=crop&q=60',
        ]);

        $p9 = Product::create([
            'category_id' => $catLens->id,
            'sku' => 'LEN-SON-001',
            'name' => 'Sony FE 50mm f/1.4 GM (G Master)',
            'description' => 'Lensa prime ultra tajam dengan bokeh lembut dan motor linear XD ganda untuk autofocus kilat.',
            'purchase_price' => 18000000,
            'selling_price' => 21900000,
            'stock' => 9,
            'minimum_stock' => 3,
            'image' => 'https://images.unsplash.com/photo-1542567455-cd733fb23fec?w=500&auto=format&fit=crop&q=60',
        ]);

        $p10 = Product::create([
            'category_id' => $catAcc->id,
            'sku' => 'ACC-TRP-001',
            'name' => 'Manfrotto Befree Advanced Carbon Fiber Tripod',
            'description' => 'Tripod kokoh dan sangat ringan untuk travel photographer, kapasitas beban hingga 9 kg.',
            'purchase_price' => 3500000,
            'selling_price' => 4800000,
            'stock' => 14,
            'minimum_stock' => 4,
            'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=500&auto=format&fit=crop&q=60',
        ]);

        $p11 = Product::create([
            'category_id' => $catAcc->id,
            'sku' => 'ACC-MEM-001',
            'name' => 'SanDisk Extreme PRO SDXC UHS-I 128GB (200MB/s)',
            'description' => 'Memory card kecepatan tinggi V30 untuk perekaman video 4K UHD tanpa stuttering.',
            'purchase_price' => 350000,
            'selling_price' => 520000,
            'stock' => 40,
            'minimum_stock' => 10,
            'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500&auto=format&fit=crop&q=60',
        ]);

        $p12 = Product::create([
            'category_id' => $catAcc->id,
            'sku' => 'ACC-FLS-001',
            'name' => 'Godox V860III TTL Li-ion Flash Speedlite',
            'description' => 'Flash kamera bertenaga baterai lithium dengan modeling light LED dan sistem wireless Godox X.',
            'purchase_price' => 2300000,
            'selling_price' => 3100000,
            'stock' => 1, // Low stock on purpose
            'minimum_stock' => 3,
            'image' => 'https://images.unsplash.com/photo-1512499617640-c74ae3a79d37?w=500&auto=format&fit=crop&q=60',
        ]);

        // 6. SEED SAMPLE PURCHASES (Stock-In)
        $pur1 = Purchase::create([
            'supplier_id' => $supCanon->id,
            'user_id' => $staff->id, // Staff creates stock-in
            'purchase_number' => 'PUR-20261025-0001',
            'total' => 190000000,
            'status' => 'completed',
            'notes' => 'Pasokan awal kamera & lensa Canon kuartal 4.',
            'purchased_at' => now()->subDays(5),
        ]);

        $pur1->purchaseItems()->createMany([
            [
                'product_id' => $p1->id,
                'quantity' => 5,
                'unit_cost' => 32000000,
                'subtotal' => 160000000,
            ],
            [
                'product_id' => $p8->id,
                'quantity' => 1,
                'unit_cost' => 30000000,
                'subtotal' => 30000000,
            ],
        ]);

        $pur2 = Purchase::create([
            'supplier_id' => $supSony->id,
            'user_id' => $admin->id, // Admin creates stock-in
            'purchase_number' => 'PUR-20261028-0002',
            'total' => 129000000,
            'status' => 'completed',
            'notes' => 'Restock Sony A7 IV dan Lensa GM.',
            'purchased_at' => now()->subDays(2),
        ]);

        $pur2->purchaseItems()->createMany([
            [
                'product_id' => $p2->id,
                'quantity' => 3,
                'unit_cost' => 31000000,
                'subtotal' => 93000000,
            ],
            [
                'product_id' => $p9->id,
                'quantity' => 2,
                'unit_cost' => 18000000,
                'subtotal' => 36000000,
            ],
        ]);

        // 7. SEED SAMPLE SALES (Stock-Out)
        $sale1 = Sale::create([
            'customer_id' => $cust2->id, // Studio Foto Mentari
            'user_id' => $staff->id,
            'sale_number' => 'SAL-20261027-0001',
            'total' => 72500000,
            'status' => 'completed',
            'notes' => 'Pengadaan kamera studio utama paket lengkap.',
            'sold_at' => now()->subDays(3),
        ]);

        $sale1->saleItems()->createMany([
            [
                'product_id' => $p1->id,
                'quantity' => 1,
                'unit_price' => 37500000,
                'subtotal' => 37500000,
            ],
            [
                'product_id' => $p8->id,
                'quantity' => 1,
                'unit_price' => 35000000,
                'subtotal' => 35000000,
            ],
        ]);

        $sale2 = Sale::create([
            'customer_id' => $cust1->id, // Reza Rahardian
            'user_id' => $pelanggan->id, // Customer online order
            'sale_number' => 'SAL-20261029-0002',
            'total' => 8720000,
            'status' => 'completed',
            'notes' => 'Pemesanan Online: Tolong packing kayu dan bubble wrap tebal.',
            'sold_at' => now()->subDay(),
        ]);

        $sale2->saleItems()->createMany([
            [
                'product_id' => $p6->id, // GoPro Hero 12
                'quantity' => 1,
                'unit_price' => 8200000,
                'subtotal' => 8200000,
            ],
            [
                'product_id' => $p11->id, // SD Card
                'quantity' => 1,
                'unit_price' => 520000,
                'subtotal' => 520000,
            ],
        ]);
    }
}
