<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('body_conditions', function (Blueprint $table) {
        $table->decimal('bmi', 5, 2)->nullable()->after('allergies');
        $table->string('body_status')->nullable();
    });
}

public function down()
{
    Schema::table('body_conditions', function (Blueprint $table) {
        $table->dropColumn('bmi');
        $table->dropColumn('body_status');
    });
}
};
