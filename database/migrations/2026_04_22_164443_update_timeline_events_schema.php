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
        Schema::table('timeline_events', function (Blueprint $table) {
            $table->dropColumn(['month_year', 'impact_description']);
            $table->date('event_date')->nullable()->after('id');
            $table->text('global_event_excerpt')->nullable()->after('global_event_title');
            
            $table->string('impact_title')->nullable()->after('global_event_link');
            $table->text('impact_excerpt')->nullable()->after('impact_title');
            $table->string('impact_link')->nullable()->after('impact_excerpt');
        });
    }

    public function down(): void
    {
        Schema::table('timeline_events', function (Blueprint $table) {
            $table->string('month_year')->nullable();
            $table->text('impact_description')->nullable();
            
            $table->dropColumn([
                'event_date',
                'global_event_excerpt',
                'impact_title',
                'impact_excerpt',
                'impact_link'
            ]);
        });
    }
};
