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

class Receipts extends Component
{
    use WithPagination;
    public $pagination = 10;
    public $showModal= false;
    public $search,$record_id;
    public $can_create_receipt = false;

    public $purchases,$purchase,$purchase_details;
    public $purchase_id,$folio,$date,$reference,$amoun,$notes,$status;
    public $receipt_id=null;
    public $receipt;
    public $max_date;

    protected function rules()
    {
        return [
            'purchase_id' => [
                'required',
                Rule::exists('purchases','id'),
            ],
            'folio' => [
                'required',
                Rule::unique('receipts'),
            ],
            'date' => 'required',
            'reference' => 'required|max:30',
        ];
    }
    #[Validate('required|numeric|regex:/^\d+(\.\d{1,2})?$/')]


    public function render()
    {
        return view('livewire.receipts.receipts',[
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

    public function showModal(bool $open=true){
        $this->showModal = $open;
    }

    public function getPurchases(){
        $this->purchases = Purchase::status('pendiente')->get();
        $this->can_create_receipt = $this->purchases->count();
    }

    public function create_receipt(){
        $this->showModal();
        $this->resetInputFields();
        $this->getPurchases();
        $this->calculateMaxDate();
        $this->date = Carbon::now()->format('Y-m-d'); // Asigna la fecha actual
    }



    private function resetInputFields()
    {
        $this->reset('record_id');
        $this->reset('purchase_id','folio','date','notes');
    }
    public function store_receipt(){
        $this->validate();
                try {
            $this->receipt = Receipt::create([
                'purchase_id'   => $this->purchase_id,
                'folio'         => $this->folio,
                'date'          => $this->date,
                'amount'        => $this->amount,
                'reference'     => $this->reference,
                'notes'         => $this->notes,
                'user_id'       => Auth::user()->id,
                'status'        => StatusReceiptEnum::abierto
            ]);

            $this->resetErrorBag();
            $this->resetInputFields();
            $this->showModal(false);

            // $this->purchase_details = $this->read_purchase_details($this->purchase_id);


        } catch (\Exception $e) {
            $this->resetErrorBag();
            $this->resetInputFields();
            $this->showModal(false);
            Log::error('Error al crear Recepción de Material:', ['error' => $e->getMessage()]);
            $this->addError('error', 'Error al actualizar generar Recepción de Material.');

        }

    }

    public function read_purchase_details(Purchase $purchase)
    {
        $this->reset('purchase_details');
        return $this->purchase->pendings_to_receive;
    }

    public function calculateMaxDate(){
        $this->max_date = Carbon::now()->format('Y-m-d');
    }
}
