<?php namespace BootstrapHunter\Projects\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBootstraphunterProjectsProjects extends Migration
{
    public function up()
    {
        Schema::table('bootstraphunter_projects_projects', function($table)
        {
            $table->timestamp('created_at')->nullable()->default('NULL');
            $table->timestamp('updated_at')->nullable()->default('NULL');
        });
    }
    
    public function down()
    {
        Schema::table('bootstraphunter_projects_projects', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
