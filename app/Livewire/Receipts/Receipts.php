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
use App\Models\PurchaseDetail;
use App\Models\ReceiptDetail;
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

    public $purchase_id, $folio, $date, $reference, $amount, $tax, $total, $notes, $status;
    public $receipt;
    public $max_date;
    // Productos de la orden de compra
    public $purchases, $purchase, $purchase_details, $purchase_detail_id, $purchase_item;

    // Productos de la recepción:
    public $receipt_id = null;
    public $receipt_detail, $receipt_detail_id;
    public $receipt_product_id, $receipt_quantity, $receipt_cost, $receipt_product_name, $max_receipt_quantity;
    public $product_in_receipt = false;
    public $receipt_details;



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
                Rule::unique('receipts')->ignore($this->record_id ?? 0)
            ],
            'date' => 'required',
            'reference' => 'required|max:30',
        ];
    }



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
        $this->purchases = Purchase::status('pendiente')->select('id', 'folio')->get();
        $this->can_create_receipt = $this->purchases->count();
    }

    /**
     * Preparar para crear una recepción
     * @return void
     */
    public function create_receipt()
    {

        $this->showModal();
        $this->resetErrorBag();
        $this->resetInputFields();
        $this->getPurchases();
        $this->calculateMaxDate();
        $this->resetPurchaseDetailFields();

        $this->date = Carbon::now()->format('Y-m-d'); // Asigna la fecha actual
        $maxFolio = Receipt::max('folio');
        $this->folio = $maxFolio ? $maxFolio + 1 : 1; // Si no hay registros, asigna 1

    }

    /**
     * Editar la recepción
     */
    public function edit(Receipt $record)
    {
        $this->resetInputFields();
        $this->getPurchases();
        $this->calculateMaxDate();
        $this->resetPurchaseDetailFields();
        $this->reset('purchase_detail_id');
        $this->receipt      = $record;
        $this->record_id    = $record->id;
        $this->receipt_id   = $record->id;
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
        $this->read_purchase_details($record->purchase_id);
        $this->receipt_details = $record->details;
    }

    private function resetPurchaseDetailFields()
    {
        $this->reset('receipt_detail_id', 'product_in_receipt', 'receipt_product_id', 'receipt_quantity', 'receipt_cost', 'receipt_product_name', 'max_receipt_quantity');
    }
    /**
     * Lee detalle, partidas de la recepción
     */


    private function resetInputFields()
    {
        $this->reset('record_id');
        $this->reset('purchase_details');
        $this->reset('purchase_id', 'folio', 'date', 'reference', 'notes', 'amount', 'tax', 'total');
    }
    /**
     * Almacena, crea la recepción
     * @return void
     */
    public function store_receipt()
    {

        $validatedData = $this->validate();


        try {
            $this->receipt = Receipt::updateOrCreate(
                ['id' => $this->record_id],
                [

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
                ]
            );
            $this->record_id = $this->receipt->id;
            $this->receipt_id = $this->receipt->id;
            $this->resetPurchaseDetailFields();

            $this->purchase_details = $this->read_purchase_details($this->purchase_id);
        } catch (\Exception $e) {
            Log::error('Error al crear o actualizar la Recepción de material:', ['error' => $e->getMessage()]);
            $this->addError('error', 'Error al crear o actualizar la Recepción de material.');
            dd($e->getMessage());
        }
    }

    public function read_purchase_details($purchase_id)
    {
        $this->reset('purchase_details');
        $purchase = Purchase::findOrFail($purchase_id);
        return $this->purchase_details = $purchase->pendings_to_receive;
    }

    public function calculateMaxDate()
    {
        $this->max_date = Carbon::now()->format('Y-m-d');
    }

    public function lockPurchaseId($purchase_id)
    {
        $purchase = Purchase::findOrFail($purchase_id);
        if ($purchase) {
            dd($purchase->has_pendings_to_receive);
            return $purchase->has_pendings_to_receive;
        }
    }

    public function calculateTaxAndTotal()
    {
        $this->reset('tax', 'total');
        if ($this->amount && isNan($this->amount)) {
            $this->tax = round(($this->amount * ($this->tax_porcentage / 100)), 2);
            $this->total = round($this->amount + $this->tax, 2);
        }
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->details()->delete();
        $receipt->delete();
    }

    /**
     * Lee el producto de la orden de compra
     */

    public function read_purchase_item()
    {
        $this->resetErrorBag();
        $this->reset('purchase_item', 'receipt_detail');
        $this->resetPurchaseDetailFields();
        if ($this->purchase_detail_id) {
            $this->purchase_item = PurchaseDetail::findOrFail($this->purchase_detail_id);
        }
        if ($this->purchase_item) {
            $this->max_receipt_quantity =  $this->purchase_item->quantity - $this->purchase_item->quantity_received;

            $this->receipt_detail = ReceiptDetail::where('receipt_id', $this->receipt_id)
                ->where('product_id', $this->purchase_item->product_id)
                ->first();
            if ($this->receipt_detail) {
                $this->receipt_detail_id =  $this->receipt_detail->id;
                $this->receipt_product_id = $this->receipt_detail->product_id;
                $this->receipt_quantity = $this->receipt_detail->quantity;
                $this->receipt_cost = $this->receipt_detail->cost;
                $this->receipt_product_name =  $this->receipt_detail->product->name;
            } else {
                $this->receipt_detail_id =  null;
                $this->receipt_product_id = $this->purchase_item->product_id;
                $this->receipt_quantity = $this->purchase_item->quantity;
                $this->receipt_cost = $this->purchase_item->cost;
                $this->receipt_product_name = $this->purchase_item->product->name;
            }

        }
        $this->product_in_receipt = $this->receipt_detail ? true : false;
    }

    public function read_receipt_item(ReceiptDetail $receiptDetail){
        $purchase_item = PurchaseDetail::where('purchase_id', $receiptDetail->receipt->purchase_id)
                                ->where('product_id',$receiptDetail->product_id)
                                ->firstOr();
        $this->purchase_detail_id = $purchase_item->id;
        $this->read_purchase_item();

        // $this->max_receipt_quantity =  $purchase_item->quantity - $purchase_item->quantity_received;

        // $this->resetPurchaseDetailFields();
        // if($receiptDetail){
        //     $this->receipt_detail_id =  $receiptDetail->id;
        //     $this->receipt_product_id = $receiptDetail->product_id;
        //     $this->receipt_quantity = $receiptDetail->quantity;
        //     $this->receipt_cost = $receiptDetail->cost;
        //     $this->receipt_product_name =  $receiptDetail->product->name;
        //     $this->max_receipt_quantity = $receiptDetail->receipt->purchase;
        // }
    }

    public function store_receipt_detail(ReceiptDetail $receipt_detail)
    {

        $this->validate([
            'receipt_product_id' => 'required',
            'receipt_cost' => 'required',
            'receipt_quantity' => 'required|min:1,numeric'
        ]);

        try {
            if ($this->receipt_detail_id) {
                $receipt_detail  = ReceiptDetail::findOrFail($this->receipt_detail_id);
                if ($receipt_detail) {
                    $receipt_detail->quantity = $this->receipt_quantity;
                    $receipt_detail->cost = $this->receipt_cost;
                    $receipt_detail->save();
                    $this->reset('receipt_details');
               }
            } else {
                $receipt_detail = ReceiptDetail::create([
                    'receipt_id'    => $this->receipt_id,
                    'product_id'    => $this->receipt_product_id,
                    'quantity'      => $this->receipt_quantity,
                    'cost'          => $this->receipt_cost,
                ]);
            }
            $this->resetPurchaseDetailFields();
            $this->reset('purchase_detail_id');
            $this->receipt_details = $this->receipt->details;
        } catch (\Exception $e) {
            Log::error('Error al crear o actualizar Producto en Recepción de Material:', ['error' => $e->getMessage()]);
            $this->addError('error', 'Error al crear o actualizar Producto en Recepción de Material:');
        }
    }

    /**
     * Eliminar partida de la recepción de material
     */

     public function destroy_receipt_detail(ReceiptDetail $receipt_detail){
        $receipt_detail->delete();
        $this->resetPurchaseDetailFields();
        $this->reset('purchase_detail_id');
        $this->receipt_details = $this->receipt->details;
     }
}
