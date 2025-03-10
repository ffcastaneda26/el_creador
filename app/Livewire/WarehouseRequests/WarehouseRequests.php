<?php

namespace App\Livewire\WarehouseRequests;

use Livewire\Component;

use App\Models\City;
use App\Models\State;
use App\Models\Client;
use App\Models\Country;
use App\Models\Zipcode;
use App\Models\Municipality;
use App\Models\WarehouseRequest;
use App\Models\WarehouseRequestDetail;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;

class WarehouseRequests extends Component
{
    use WithPagination;
    public $pagination = 10;
    public $showModal= false;
    public $search,$record_id;

    public $warehouse_request_record;
    public $warehouse_request_details;



    /** Presenta resultados */
    public function render()
    {
        return view('livewire.warehouse-requests.warehouse-requests',[
            'records' => $this->getRecords(),
        ])->title(__('Warehouse Requests'));
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

    public function store(){

        $this->resetErrorBag();

        $this->resetInputFields();

        $this->showModal(false);
    }
    /**
     * Enciende o Apaga variable para mostrar u ocultar modal
     * @param bool $open
     * @return void
     */
    public function showModal(bool $open=true){
        $this->showModal = $open;
    }


    public function suply(WarehouseRequest $record){
        $this->resetInputFields();
        $this->record_id = $record->id;
        $this->warehouse_request_record = $record;
        $this->warehouse_request_details = $this->warehouse_request_record->details;

        $this->showModal = true;
    }
    /**
     * Restaura variables
     * @return void
     */
    private function resetInputFields()
    {
        $this->reset('record_id');

    }

    /**
     * Elimina el Cliente
     * @param \App\Models\Client $record
     * @return void
     */


}

