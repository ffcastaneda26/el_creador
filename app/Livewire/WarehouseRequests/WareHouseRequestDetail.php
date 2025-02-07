<?php

namespace App\Livewire\WarehouseRequests;

use Livewire\Component;
use App\Models\WarehouseRequestDetail as WareHouseRequestItem;

class WareHouseRequestDetail extends Component
{
    public $ware_request_detail;
    public $quantity;
    public $quantity_error  =false;
    public function render()
    {
          return view('livewire.warehouse-requests.ware-house-request-detail');
    }

        /**
     * Actualiza la cantidad y el estado en dado caso
     *
     */
    public function updateItem()
    {   
        // $this->quantity_error  =!$this->validate_quantity();
        if(!$this->validate_quantity()){
            return;
        }

         if($this->ware_request_detail->getPending() == 0 || $this->quantity_error   ){
            return;
        }

        try {
            $this->ware_request_detail->updateDelivery($this->quantity);
            $this->reset('quantity','quantity_error');
            $this->ware_request_detail->warehouse_request->updateStatus();
            $this->ware_request_detail->refresh();

        } catch (\Throwable $th) {
            dd('Se presentó un error, por favor avise al administrador',$th->getMessage());
        }
    }

    public function validate_quantity(): bool
    {
        $this->reset('quantity_error');
        return $this->quantity <= $this->ware_request_detail->getPending() == 0 || $this->ware_request_detail->quantity - $this->ware_request_detail->quantity_delivered;
    }
}
