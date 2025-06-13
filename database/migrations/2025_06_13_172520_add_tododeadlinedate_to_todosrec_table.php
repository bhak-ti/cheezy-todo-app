<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTododeadlinedateToTodosrecTable extends Migration
{
    public function up()
    {
        Schema::table('TODOSREC', function (Blueprint $table) {
            $table->timestamp('TODODEADLINEDATE')->nullable()->after('TODOFINISHTIMESTAMP');
        });
    }

    public function down()
    {
        Schema::table('TODOSREC', function (Blueprint $table) {
            $table->dropColumn('TODODEADLINEDATE');
        });
    }
}
