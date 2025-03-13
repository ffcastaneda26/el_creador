<?php

namespace App\Livewire\Receipts;

use Carbon\Carbon;
use App\Models\Receipt;
use Livewire\Component;
use App\Models\Purchase;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Enums\Enums\StatusReceiptEnum;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNan;

class Receipts extends Component
{
    use WithPagination;
    public $pagination = 10;
    public $showModal = false;
    public $search, $record_id;
    public  $lock_purchase_id_on_edit = false;
    public $can_create_receipt = false;
    public $tax_porcentage = 16;
    public $purchases, $purchase, $purchase_details;
    public $purchase_id, $folio, $date, $reference, $amount, $tax,$total,$notes, $status;
    public $receipt_id = null;
    public $receipt;
    public $max_date;

    protected function rules()
    {
        return [
            'purchase_id' => [
                'required',
                Rule::exists('purchases', 'id'),
            ],
            'folio' => [
                'required',
                'min:1',
                'numeric',
                Rule::unique('receipts'),
            ],
            'date' => 'required',
            'reference' => 'required|max:30',
        ];
    }

    // #[Validate('required|numeric|regex:/^\d+(\.\d{1,2})?$/')]


    public function render()
    {
        return view('livewire.receipts.receipts', [
            'records' => $this->getRecords(),
        ])->title(__('Material Receptions'));
    }

    public function getRecords()
    {
        $qry  = Receipt::query();
        $qry = $qry->when($this->search, function ($query) {
            return $query->where('folio', 'like', "%{$this->search}%");
        });
        return  $qry->paginate($this->pagination);
    }

    public function showModal(bool $open = true)
    {
        $this->showModal = $open;
    }

    public function getPurchases()
    {
        $this->purchases = Purchase::status('pendiente')->select('id','folio')->get();
        $this->can_create_receipt = $this->purchases->count();
    }

    /**
     * Preparar para crear una recepción
     * @return void
     */
    public function create_receipt()
    {
        $this->showModal();
        $this->resetInputFields();
        $this->getPurchases();
        $this->calculateMaxDate();
        $this->date = Carbon::now()->format('Y-m-d'); // Asigna la fecha actual
    }

    /**
     * Editar la recepción
     */
    public function edit(Receipt $record)
    {
        $this->resetInputFields();
        $this->getPurchases();
        $this->calculateMaxDate();
        $this->record_id    = $record->id;
        $this->purchase_id  = $record->purchase_id;
        $this->folio        = $record->folio;
        $this->date         = $record->date->format('Y-m-d');
        $this->reference    = $record->reference;
        $this->amount       = $record->amount;
        $this->tax          = $record->tax;
        $this->total        = $record->total;

        $this->notes        = $record->notes;
        $this->lock_purchase_id_on_edit = $record->has_details();
        $this->showModal = true;
    }

    private function resetInputFields()
    {
        $this->reset('record_id');
        $this->reset('purchase_id', 'folio', 'date', 'notes','amount','tax','total');
    }
    /**
     * Almacena, crea la recepción
     * @return void
     */
    public function store_receipt()
    {
        $validatedData =$this->validate();
        $receipt = Receipt::create([
            'purchase_id'   => $this->purchase_id,
            'folio'         => $this->folio,
            'date'          => $this->date,
            'amount'        => $this->amount,
            'tax'           => $this->tax,
            'total'        => $this->total,
            'reference'     => $this->reference,
            'notes'         => $this->notes,
            'user_id'       => Auth::user()->id,
            'status'        => StatusReceiptEnum::abierto
        ]);
        $purchase = Purchase::findOrFail($this->purchase_id);
        $this->purchase_details = $purchase->pendings_to_receive;


    }

    public function read_purchase_details(Purchase $purchase)
    {
        $this->reset('purchase_details');
        dd($this->purchase->details());
        return $this->purchase->details();
    }

    public function calculateMaxDate()
    {
        $this->max_date = Carbon::now()->format('Y-m-d');
    }

    public function lockPurchaseId($purchase_id){
        $purchase = Purchase::findOrFail($purchase_id);
        if($purchase){
            dd($purchase->has_pendings_to_receive);
            return $purchase->has_pendings_to_receive;
        }
    }

    public function calculateTaxAndTotal(){
        $this->reset('tax','total');
        if($this->amount && isNan($this->amount)){
            $this->tax = round(($this->amount * ($this->tax_porcentage/100)),2);
            $this->total = round($this->amount + $this->tax,2);
        }
    }

    public function destroy(Receipt $receipt){
        $receipt->details()->delete();
        $receipt->delete();
    }
}
