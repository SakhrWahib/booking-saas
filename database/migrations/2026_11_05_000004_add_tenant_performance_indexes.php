<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// فهارس مركّبة لأكثر الاستعلامات تكرارًا بعد تعدد الأنشطة (كل استعلام يبدأ بـ tenant_id)
return new class extends Migration
{
    private const INDEXES = [
        'bookings' => [['tenant_id', 'booking_date'], ['tenant_id', 'status'], ['tenant_id', 'created_at']],
        'customers' => [['tenant_id', 'created_at']],
        'products' => [['tenant_id', 'is_active']],
        'invoices' => [['tenant_id', 'created_at']],
    ];

    public function up(): void
    {
        foreach (self::INDEXES as $table => $indexes) {
            Schema::table($table, function (Blueprint $t) use ($indexes) {
                foreach ($indexes as $columns) {
                    $t->index($columns);
                }
            });
        }
    }

    public function down(): void
    {
        foreach (self::INDEXES as $table => $indexes) {
            Schema::table($table, function (Blueprint $t) use ($indexes) {
                foreach ($indexes as $columns) {
                    $t->dropIndex($columns);
                }
            });
        }
    }
};
