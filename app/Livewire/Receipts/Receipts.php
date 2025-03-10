<?php

namespace App\Livewire\Receipts;

use App\Enums\Enums\StatusPurchaseEnum;
use App\Models\Purchase;
use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;

class Receipts extends Component
{
    use WithPagination;

    // Variables generales
    public $pagination = 10;
    public $showModal = false;
    public $search, $record_id;
    public $purchase_status;
    public $receipt_record;
    public $receipt_details;

    public $create_receipt = false;
    public $can_create_receipts = true;
    // Del registro9 padre de la recepción;
    public $purchase_id;

    public $folio;
    public $date;
    public $amount;
    public $notes;
    public $status;

    public $quantity= 0;
    public $max_quantity;

    public $purchase_orders;
    public function mount()
    {
        $this->purchase_orders = Purchase::where('status', StatusPurchaseEnum::pendiente)->get();
    }
    public function render()
    {
        return view('livewire.receipts.receipts', [
            'records' => $this->getRecords(),
        ])->title(__('Material Receptions'));;
    }

    /**
     * Lee Órdenes de compra
     * @return never
     */

    public function getRecords()
    {
        $qry  = Receipt::query();
        $qry = $qry->when($this->search, function ($query) {
            return $query->where('folio', 'like', "%{$this->search}%");
        })->when($this->purchase_status, function ($query) {
            return $query->where('status', '=', $this->purchase_status);
        });
        return  $qry->paginate($this->pagination);
    }
    function create()
    {
        $this->purchase_orders = Purchase::where('status','pendiente')->get();
        if($this->purchase_orders->count()){
            $this->create_receipt = true;
        }else{
            $this->can_create_receipts = false;
        }

        //  dd('Ordenes de compra para recepciones de material',$this->purchase_orders,'crear recepcion?',$this->create_receipt, $this->can_create_receipts);
    }
}
