<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfers', function(Blueprint $table) {
            $table->addColumn('float', 'approved');
            $table->addColumn('text', 'comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfers', function(Blueprint $table) {
            $table->dropColumn('approved');
            $table->dropColumn('comment');
        });
    }
}
