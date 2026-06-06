<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offerwalls', function (Blueprint $table) {
            // Widget embed code (JS snippet from notik.io, CPALead, etc.)
            $table->text('widget_script')->nullable()->after('iframe_url_template');
            // Widget display mode: 'iframe' | 'widget' | 'both'
            $table->string('display_mode', 20)->default('iframe')->after('widget_script');
        });
    }

    public function down(): void
    {
        Schema::table('offerwalls', function (Blueprint $table) {
            $table->dropColumn(['widget_script', 'display_mode']);
        });
    }
};
