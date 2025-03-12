<?php

namespace App\Livewire\Receipts;

use App\Models\Purchase;
use App\Models\Receipt;
use Livewire\Component;

use App\Models\WarehouseRequest;
use Livewire\WithPagination;

class Receipts extends Component
{
    use WithPagination;
    public $pagination = 10;
    public $showModal = false;
    public $showFormCreate = false;
    public $search, $record_id;
    public $create_receipt = false;
    public $can_create_receipts = true;
    public $receipt_record;
    public $receipt_status;

    public $receipt_record_details;

    /** Variables del registro padre */
    public $purchases, $purchase, $purchase_id, $purchase_details;
    public $folio, $date, $amount, $reference, $notes;


    /** Presenta resultados */
    public function render()
    {
        return view('livewire.receipts.receipts', [
            'records' => $this->getRecords(),
        ])->title(__('Material Receptions'));
    }


    /**
     * Lee Requerimientos al almacén
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getRecords()
    {
        $qry  = WarehouseRequest::query();
        $qry = $qry->when($this->search, function ($query) {
            return $query->where('folio', 'like', "%{$this->search}%");
        });
        return  $qry->paginate($this->pagination);
    }


    /**
     * Valida y en su caso guarda los datos
     */

    public function store()
    {

        $this->resetErrorBag();

        $this->resetInputFields();

        $this->showModal(false);
    }
    /**
     * Enciende o Apaga variable para mostrar u ocultar modal
     * @param bool $open
     * @return void
     */
    public function showModal(bool $open = true)
    {
        $this->showModal = $open;
        $this->showFormCreate = false;
    }


    public function suply(Receipt $record)
    {
        $this->resetInputFields();
        $this->record_id = $record->id;
        $this->receipt_record = $record;
        $this->receipt_record_details = $this->receipt_record->details;
        $this->showModal = true;
    }
    /**
     * Restaura variables
     * @return void
     */
    private function resetInputFields()
    {
        $this->reset('record_id');
        $this->reset('purchase_id', 'folio', 'date', 'amount', 'reference', 'notes');
    }

    /**
     * Abrir Modal
     */
    public function create()
    {
        $this->resetInputFields();
        $this->reset('receipt_record');
        $this->showFormCreate = true;
        $this->showModal = true;

        $this->purchases = Purchase::status('pendiente')->get();
    }

    /**
     * Leer la orden de compra
     */
    public function read_purchase()
    {
        $this->reset('purchase');
        if ($this->purchase_id) {
            $this->purchase = Purchase::findOrFail($this->purchase_id);
            if ($this->purchase) {
                $this->purchase_details = $this->purchase->pendings_to_receive;
            }
        }
    }

    /**
     * Validar y en su caso crear la recepción de material
     */

     public function create_receipt(){
        dd('Aquí es donde se va a crear la recepción de material');
     }

}
