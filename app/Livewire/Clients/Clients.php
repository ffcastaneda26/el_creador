<?php

namespace App\Livewire\Clients;

use App\Models\City;
use App\Models\State;
use App\Models\Client;
use App\Models\Country;
use App\Models\Zipcode;
use Livewire\Component;
use App\Models\Municipality;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;

class Clients extends Component
{
    use WithPagination;
    public $pagination = 10;
    public $showModal= false;
    public $search,$record_id;

    public $countries,$states,$municipalities,$cities,$colonies;
    public $country_id,$state_id,$municipality_id,$city_id,$colony_id;
    public $mobile,$curp,$ine,$rfc,$address,$colony,$references,$zipcode,$notes;


    #[Validate('required|min:3')]
    public $name;
    #[Validate('required|email:3')]
    public $email;

    #[Validate('required|numeric')]
    public $phone;
    #[Validate('required')]
    public $type,$tax_type;

    public function mount(){
        $this->countries = Country::Include()->pluck('id','country');
        $this->country_id = 135;
        $this->read_states();
    }

    /** Presenta resultados */
    public function render()
    {
        return view('livewire.clients.clients',[
            'records' => $this->getClients(),
        ])->title(__('Clients'));
    }

    /** Lee Clientes */
    /**
     * Lee clientes considerando la busqueda por: Nombre,Teléfono y Celular
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getClients()
    {
        $qry  = Client::query();
        $qry = $qry->when($this->search, function ($query) {
            return $query->where('name', 'like', "%{$this->search}%")
                ->orwhere('email', 'like', "%{$this->search}%")
                ->orwhere('phone', 'like', "%{$this->search}%")
                ->orwhere('mobile', 'like', "%{$this->search}%");
        });
        return  $qry->paginate($this->pagination);
    }



    /** Lee Entidades Federativas */
    /**
     * Lee entidades federativas cuando se cambia de país
     * @return void
     */
    public function read_states(){
        // $this->reset('states','municipalities','cities','colonies');
        $this->reset('states');

        if(!$this->country_id){
            return;
        }
        $country = Country::findOrFail($this->country_id);

        $this->states = $country->states()->orderBy('name')->select('id','name')->get();
        if($this->state_id){
            $this->read_municipalities();
        }

    }


    /**
     * Lee municipios cuando se cambia de entidad federativa
     * @return void
     */
    public function read_municipalities(){

        $this->reset('municipalities','cities','colonies');
        if(!$this->state_id){
            return;
        }
        $state = State::findOrFail($this->state_id);
        $this->municipalities = $state->municipalities()->orderBy('name')->select('id','name')->get();

        if($this->municipality_id){
            $this->read_cities();
        }
    }


    /**
     * Lee ciudades cuando se cambia de municipio
     * @return void
     */
    public function read_cities(){
        $this->reset('cities','colonies');
        if(!$this->municipality_id){
            return;
        }
        $municipality = Municipality::findOrFail($this->municipality_id);
        $this->cities = $municipality->cities()->orderBy('name')->select('id','name')->get();
        if($this->city_id){
            $this->read_colonies();
        }
    }

    /**
     * Lee colonias cuando se cambia de ciudad
     * @return void
     */
    public function read_colonies(){
        $this->reset('colonies');
        if(!$this->city_id){
            return;
        }

        $city = city::findOrFail($this->city_id)->first();
        $this->colonies = $city->colonies()->pluck('id','name');
    }

    /**
     * Lee código postal y asigna valores a país,entidad,municipio, ciudad
     * @return void
     */
    public function read_zipcode(){

        if(!$this->zipcode || strlen($this->zipcode) != 5){
            return;
        }

        $zipcodes = Zipcode::where('zipcode',$this->zipcode)->get();
        $this->colonies = $zipcodes;
        if($zipcodes->count()){
            $this->reset('states','municipalities','cities');
            $this->reset('country_id','state_id','municipality_id','city_id');
            foreach($zipcodes as $zipcode){
                $this->country_id       = $zipcode->country_id;
                $this->state_id         = $zipcode->state_id;
                $this->municipality_id  = $zipcode->municipality_id;
                $this->city_id          = $zipcode->city_id;
                $this->read_states();
                $this->read_municipalities();
                $this->read_cities();
                break;
            }
            $this->colonies = $zipcodes;
        }
    }

    /**
     * Asigna nombre de colonia cuando se selecciona de la lista
     * @return void
     */
    public function asign_colony(){
        if(!$this->colony_id){
            return;
        }
        $colony = Zipcode::findOrFail($this->colony_id);
        if($colony){
            $this->colony = $colony->name;
        }
    }

    /**
     * Valida y en su caso guarda los datos
     */

    public function store(){
            //  TODO:: Agrer validaciones mas complejas como el CURP, RFC, etc.
        $this->resetErrorBag();
        $this->validate();
        try {
            Client::updateOrCreate(
                ['id' => $this->record_id],
                [
                        'name'          => $this->name,
                        'email'         => $this->email,
                        'phone'         => $this->phone,
                        'mobile'        => $this->mobile,
                        'curp'          => $this->curp,
                        'ine'           => $this->ine,
                        'rfc'           => $this->rfc,
                        'type'          => $this->type,
                        'tax_type'      => $this->tax_type,
                        'address'       => $this->address,
                        'colony'        => $this->colony,
                        'references'    => $this->references,
                        'zipcode'       => $this->zipcode,
                        'country_id'     => $this->country_id,
                        'state_id'       => $this->state_id,
                        'municipality_id'=> $this->municipality_id,
                        'city_id'        => $this->city_id ,
                        'notes'          => $this->notes,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error updating client:', ['error' => $e->getMessage()]);
            $this->addError('error', 'Error al actualizar el cliente.');
        }

        $this->resetInputFields();
        $this->read_states();
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
    /**
     * Prepara para la edición
     * @param \App\Models\Client $record
     * @return void
     */
    public function edit(Client $record){
        $this->resetInputFields();
        $this->record_id = $record->id;
        $this->name     = $record->name;
        $this->email    = $record->email;
        $this->phone    = $record->phone;
        $this->mobile   = $record->mobile;
        $this->curp     = $record->curp;
        $this->ine      = $record->ine;
        $this->rfc      = $record->rfc;
        $this->type     = $record->type;
        $this->tax_type     = $record->tax_type;
        $this->address  = $record->address;
        $this->colony   = $record->colony;
        $this->references= $record->references;
        $this->zipcode   = $record->zipcode;
        $this->country_id= $record->country_id;
        $this->state_id  = $record->state_id;
        $this->municipality_id= $record->municipality_id;
        $this->city_id  = $record->city_id ;
        $this->notes    = $record->notes;

        $this->read_zipcode();
        $this->asign_colony();
        $this->showModal = true;
    }

    /**
     * Restaura variables
     * @return void
     */
    private function resetInputFields()
    {
        $this->reset('record_id');
        $this->reset('name','email','phone','mobile','curp','ine','rfc','type','tax_type','address','colony','references','zipcode','notes');
        $this->reset('state_id','municipality_id','city_id');
        $this->reset('states','municipalities','cities','colonies');
        $this->read_states();
    }

    /**
     * Elimina el Cliente
     * @param \App\Models\Client $record
     * @return void
     */
    public function destroy(Client $record)
    {
        // TODO:: Implementar validación de eliminar y eliminar en pantalla
        $this->delete($record);
    }

}
