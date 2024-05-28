<?php namespace WebBook\ResponiveImages\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableCreateWebBookResponsiveimagesInconvertables extends Migration
{
    public function up()
    {
        Schema::create('webbook_responsiveimages_inconvertibles', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('filename');
            $table->string('path');
            $table->text('error');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('webbook_responsiveimages_inconvertibles');
    }
}
