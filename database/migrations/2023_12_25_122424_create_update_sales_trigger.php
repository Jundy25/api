<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateDailySalesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER `update_daily_sales` AFTER DELETE ON `uthangs` FOR EACH ROW
            BEGIN
                DECLARE debtor_name VARCHAR(255);
                DECLARE item_price DECIMAL(8, 2);

                SELECT d_name INTO debtor_name FROM debtors WHERE d_id = OLD.d_id;
                SET item_price = OLD.price;

                INSERT INTO sales (item_id, quantity_sold, price, sale_date, debtor_name)
                VALUES (OLD.item_id, OLD.quantity, item_price, NOW(), debtor_name)
                ON DUPLICATE KEY UPDATE quantity_sold = quantity_sold + OLD.quantity;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the trigger if needed
        DB::unprepared('DROP TRIGGER IF EXISTS `update_daily_sales`');
    }
}
