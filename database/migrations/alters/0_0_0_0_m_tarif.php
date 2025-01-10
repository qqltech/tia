<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class mtarif extends Migration
{
    protected $tableName = "m_tarif";
    
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            //$table->string('_existColumnName_')->change();
            //$table->string('_columnName_');
            // $table->dropColumn(['status' ]);
            // $table->boolean('is_active')->default(1);
            // $table->string('sektor',50)->nullable()->comment('{"src":"m_general.id"}')->change();
            // $table->string('tt_elektronik')->nullable();
            // $table->decimal('tarif_ppjk',18,2)->nullable();
        });
    }
}
