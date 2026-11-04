<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// الباقات والاشتراكات ومالك المنصة
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->default(0);
            $table->unsignedInteger('max_staff')->nullable();      // null = بلا حد
            $table->unsignedInteger('max_services')->nullable();
            $table->unsignedInteger('max_monthly_bookings')->nullable();
            $table->json('features')->nullable();                  // مثل ["coupons","invoices","loyalty","reports"]
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $all = json_encode(['coupons', 'invoices', 'loyalty', 'reports', 'abandoned_carts', 'mobile_app']);
        DB::table('plans')->insert([
            ['name' => 'الأساسية', 'slug' => 'basic', 'price_monthly' => 99, 'price_yearly' => 990, 'max_staff' => 2, 'max_services' => 10, 'max_monthly_bookings' => 300, 'features' => json_encode(['invoices']), 'is_active' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الاحترافية', 'slug' => 'pro', 'price_monthly' => 249, 'price_yearly' => 2490, 'max_staff' => 10, 'max_services' => 50, 'max_monthly_bookings' => 2000, 'features' => json_encode(['invoices', 'coupons', 'reports', 'abandoned_carts']), 'is_active' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الأعمال', 'slug' => 'business', 'price_monthly' => 499, 'price_yearly' => 4990, 'max_staff' => null, 'max_services' => null, 'max_monthly_bookings' => null, 'features' => $all, 'is_active' => true, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('is_active')->constrained()->nullOnDelete();
            $table->string('subscription_status')->default('trial')->after('plan_id'); // trial, active, past_due, cancelled
            $table->date('trial_ends_at')->nullable()->after('subscription_status');
            $table->date('subscription_ends_at')->nullable()->after('trial_ends_at');
            $table->string('owner_name')->nullable();
            $table->string('owner_phone', 20)->nullable();
            $table->text('notes')->nullable();
        });

        // النشاط الأول (الحالي) على أعلى باقة ومفعّل حتى لا يتأثر شيء
        DB::table('tenants')->where('id', 1)->update([
            'plan_id' => DB::table('plans')->where('slug', 'business')->value('id'),
            'subscription_status' => 'active',
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('is_active');
        });

        // أول مستخدم في النشاط الأول هو مالك المنصة
        $ownerId = DB::table('users')->where('tenant_id', 1)->orderBy('id')->value('id');
        if ($ownerId) {
            DB::table('users')->where('id', $ownerId)->update(['is_super_admin' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_super_admin'));
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plan_id');
            $table->dropColumn(['subscription_status', 'trial_ends_at', 'subscription_ends_at', 'owner_name', 'owner_phone', 'notes']);
        });
        Schema::dropIfExists('plans');
    }
};
