<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->string('advertiser_name')->nullable()->after('title');
            $table->decimal('commission_amount', 15, 2)->default(0)->after('position');
            $table->enum('commission_type', ['percentage', 'fixed'])->default('fixed')->after('commission_amount');
            $table->date('start_date')->nullable()->after('commission_type');
            $table->date('end_date')->nullable()->after('start_date');
            $table->text('notes')->nullable()->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn([
                'advertiser_name',
                'commission_amount',
                'commission_type',
                'start_date',
                'end_date',
                'notes',
            ]);
        });
    }
};
