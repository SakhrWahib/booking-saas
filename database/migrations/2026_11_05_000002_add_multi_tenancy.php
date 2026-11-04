<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// تعدد الأنشطة: جدول tenants وعمود tenant_id في جداول النشاط.
// البيانات الموجودة تُنقل إلى النشاط رقم 1 دون حذف أي شيء.
return new class extends Migration
{
    public const TABLES = [
        'users', 'customers', 'products', 'service_providers', 'working_schedules', 'time_slots',
        'bookings', 'invoices', 'coupons', 'abandoned_carts', 'notifications', 'provider_time_offs', 'system_settings',
    ];

    // فهارس فريدة تصبح فريدة داخل النشاط فقط
    private const SCOPED_UNIQUES = [
        'customers' => 'phone',
        'coupons' => 'code',
        'system_settings' => 'key',
    ];

    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('tenants')->insert([
            'id' => 1,
            'name' => config('brand.name', 'النشاط الأول'),
            'slug' => 'main',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (self::TABLES as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
            DB::table($name)->whereNull('tenant_id')->update(['tenant_id' => 1]);
        }

        foreach (self::SCOPED_UNIQUES as $name => $column) {
            Schema::table($name, function (Blueprint $table) use ($name, $column) {
                $table->dropUnique("{$name}_{$column}_unique");
                $table->unique(['tenant_id', $column]);
            });
        }
    }

    public function down(): void
    {
        foreach (self::SCOPED_UNIQUES as $name => $column) {
            Schema::table($name, function (Blueprint $table) use ($column) {
                $table->dropUnique(['tenant_id', $column]);
                $table->unique($column);
            });
        }

        foreach (self::TABLES as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropConstrainedForeignId('tenant_id');
            });
        }

        Schema::dropIfExists('tenants');
    }
};
