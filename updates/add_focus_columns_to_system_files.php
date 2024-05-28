<?php namespace WebBook\ResponsiveImages\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddFocusColumnsToSystemFiles extends Migration
{
    public function up()
    {
        Schema::table('system_files', function ($table) {
            $table->decimal('webbook_responsiveimages_focus_x_axis', 5, 2)->nullable();
            $table->decimal('webbook_responsiveimages_focus_y_axis', 5, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('system_files', function ($table) {
            $table->dropColumn([
                'webbook_responsiveimages_focus_x_axis',
                'webbook_responsiveimages_focus_y_axis'
            ]);
        });
    }
}
