<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_statuses')->insert([
            'name' => 'Novo Pedido'
        ]);

        DB::table('order_statuses')->insert([
            'name' => 'Pedido Confirmado'
        ]);

        DB::table('order_statuses')->insert([
            'name' => 'Pronto para retirada'
        ]);

        DB::table('order_statuses')->insert([
            'name' => 'Saiu pra entrega'
        ]);

        DB::table('order_statuses')->insert([
            'name' => 'Pedido concluído'
        ]);

        DB::table('order_statuses')->insert([
            'name' => 'Pedido Cancelado'
        ]);
    }
}
