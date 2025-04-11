<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(__('Creating') . ' ' . __('Payment Methods Table'));

        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('payment_methods')->truncate();
        $sql = "INSERT INTO payment_methods VALUES
            (1,'Transferencia Bancaria',null,1),
            (2,'Depósito Bancario',null,1),
            (3,'Efectivo',null,1),
            (4,'Cheque',null,1),
            (5,'Tarjeta Débito',null,1),
            (6,'Trjeta Crédito',null,1),
            (7,'Paypal',null,1);";

        DB::update($sql);

        $this->command->info(__('Payment Methods Table') . ' ' . __('Created'));

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
