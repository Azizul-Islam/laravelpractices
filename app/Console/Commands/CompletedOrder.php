<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Console\Command;

class CompletedOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:completed-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'all the completed orders should be moved to a separate “deliveries”
    table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deliveredOrders = Order::where('order_status', 'completed')->get();
        // Move each completed order to the deliveries table
        foreach ($deliveredOrders as $order) {
            Delivery::create($order->toArray());
        }

        $this->info('all the completed orders moved to a separate “deliveries” table.');
    }
}
