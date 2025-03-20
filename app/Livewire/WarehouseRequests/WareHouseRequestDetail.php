<?php

namespace App\Livewire\WarehouseRequests;

use App\Models\KeyMovement;
use App\Models\Movement;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Livewire\Component;
use App\Models\WarehouseRequestDetail as WareHouseRequestItem;
use Illuminate\Support\Facades\Auth;

class WareHouseRequestDetail extends Component
{
    public $ware_request_detail;
    public $quantity;
    public $pending_quantity;
    public $max_quantity;
    public $quantity_error  =false;
    public $stock_available;
    public $product_warehouse;


    public function render()
    {
        $this->read_max_available_quantity();
          return view('livewire.warehouse-requests.ware-house-request-detail');
    }

    public function read_max_available_quantity(){
        $this->product_warehouse = ProductWarehouse::where('product_id',$this->ware_request_detail->product_id)->first();
        $this->stock_available = $this->product_warehouse->stock_available;
        $this->pending_quantity = $this->ware_request_detail->quantity - $this->ware_request_detail->quantity_delivered;
        $this->max_quantity = $this->stock_available >= $this->pending_quantity ? $this->pending_quantity : $this->stock_available;

    }
    /**
     * Actualiza la cantidad y el estado en dado caso
     *
     */
    public function updateItem()
    {
         if($this->ware_request_detail->getPending() == 0 || !$this->validate_quantity()   ){
            return;
        }


        try {
            $this->ware_request_detail->updateDelivery($this->quantity);
            $key_movement = KeyMovement::where('short','SolAlm')->first();
            $movement = $this->create_movement();
            $this->ware_request_detail->warehouse_request->updateStatus();
            $this->ware_request_detail->refresh();
            $this->reset('quantity','quantity_error');
        } catch (\Throwable $th) {
            dd('Se presentó un error, por favor avise al administrador',$th->getMessage());
        }
    }

    public function validate_quantity(): bool
    {
        $this->reset('quantity_error');
        return $this->quantity <= $this->ware_request_detail->getPending() == 0 || $this->ware_request_detail->quantity - $this->ware_request_detail->quantity_delivered;
    }

     /**
      * Crea movimiento de almacén
      * @return never
      */
     public function create_movement()
     {

        $key_movement = KeyMovement::where('short','SolAlm')->first();
        $movement = Movement::create([
            'warehouse_id' => $this->product_warehouse->warehouse->id,
            'product_id' => $this->ware_request_detail->product_id,
            'key_movement_id' => $key_movement->id,
            'date' => now(),
            'quantity' => $this->quantity,
            'cost' => $this->product_warehouse->average_cost,
            'amount' => $this->product_warehouse->average_cost * $this->quantity,
            'reference' => $this->ware_request_detail->warehouse_request->reference,
            'status' => 'Aplicado',
            'user_id' => Auth::user()->id,
        ]);
        return $movement ? $movement : null;
     }

     public function update_stock(Movement $movement){
        $this->product_warehouse->updateStock($movement->quantity,$movement->key_movement->type);
        $this->product_warehouse->refresh();
     }

     private function show_values(){
        $key_movement = KeyMovement::where('short','SolAlm')->first();
        dd('warehouse_id='. $this->product_warehouse->warehouse->id,
        'product_id='. $this->ware_request_detail->product_id,
        'key_movement_id='. $key_movement->id,
        'date='. now(),
        'quantity='. $this->quantity,
        'cost='. $this->product_warehouse->average_cost,
        'amount='. $this->product_warehouse->average_cost * $this->quantity,
        'reference='. $this->ware_request_detail->warehouse_request->reference,
        'status='. 'Aplicado',
        'user_id='. Auth::user()->id);
     }
}
