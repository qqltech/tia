<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class vstockitem extends Migration
{
    protected $tableName = "v_stock_item";

    public function up()
    {
        \DB::unprepared("CREATE OR REPLACE VIEW v_stock_item
            AS select 
            mi.id,
            mi.kode,
            mi.id as m_item_id,
            mi.nama_item,
            mi.tipe_item,
            mi.uom_id,
            uom.deskripsi as uom_name,
            coalesce(
                (select rsd.typemin from r_stock_d rsd 
                where rsd.m_item_id = mi.id order by rsd.created_at 
                desc limit 1
                ), null
            ) as typemin,
            coalesce(
                (
                    select 
                        rsd.qty_awal + 
                        case 
                            when rsd.typemin = 1 then rsd.qty_in 
                            else rsd.qty_out 
                        end
                    from r_stock_d rsd 
                    where rsd.m_item_id = mi.id 
                    order by rsd.created_at desc 
                    limit 1
                ), 0
            ) as qty_stock,
            coalesce(
                (select rsd.qty_awal from r_stock_d rsd 
                where rsd.m_item_id = mi.id order by rsd.created_at 
                desc limit 1
                ), 0
            ) as qty_awal,
            coalesce(
                (select rsd.qty_in from r_stock_d rsd 
                where rsd.m_item_id = mi.id order by rsd.created_at 
                desc limit 1
                ), 0
            ) as qty_in,
            coalesce(
                (select rsd.qty_out from r_stock_d rsd 
                where rsd.m_item_id = mi.id order by rsd.created_at 
                desc limit 1
                ), 0
            ) as qty_out,
            coalesce(
                (select rsd.price from r_stock_d rsd 
                where rsd.m_item_id = mi.id order by rsd.created_at 
                desc limit 1
                ), 0
            ) as price,
            coalesce(
                (select rsd.price_old from r_stock_d rsd 
                where rsd.m_item_id = mi.id order by rsd.created_at 
                desc limit 1
                ), 0
            ) as price_old,
            mi.is_active
        from m_item mi
        join set.m_general uom on uom.id = mi.uom_id;
    ");

    }
    public function down()
    {
        // if( Schema::hasTable($this->tableName) ){
        //     \Cache::put($this->tableName, \DB::table($this->tableName)->get(), 60*30 );
        // }
        Schema::dropIfExists($this->tableName);
    }
}