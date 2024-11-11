<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\Country;
use App\Models\State;
use App\Models\Municipality;
use App\Models\City;
use App\Models\Zipcode;
use Livewire\Component;
use Livewire\WithPagination;

class Clients extends Component
{
    use WithPagination;
    public $pagination = 10;
    public $showModal= false;
    public $search,$record_id;
 
    public $countries,$states,$municipalities,$cities,$colonies;
    public $country_id,$state_id,$municipality_id,$city_id,$colony_id;
    public $name,$email,$phone,$rfc,$type,$address,$colony,$references,$zipcode,$notes;
   
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
        ])->title(__('Clients'));;
    }

    /** Lee Clientes */
    /**
     * Lee clientes considerando la busqueda por: Nombre,Teléfono y Celular
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getClients()
    {
        return Client::Search($this->search)->paginate($this->pagination);

        // $qry  = Client::query();
        // $qry = $qry->when($this->search, function ($query) {
        //     return $query->where('name', 'like', "%{$this->search}%")
        //                  ->orwhere('email', 'like', "%{$this->search}%")
        //                  ->orwhere('phone', 'like', "%{$this->search}%");
        // });
        
        // return  $qry->paginate($this->pagination);
 
    }

    /** Lee Entidades Federativas */
    /**
     * Lee entidades federativas cuando se cambia de país
     * @return void
     */
    public function read_states(){
        $this->reset('states','municipalities','cities','colonies');
        if(!$this->country_id){
            return;
        }
        $country = Country::findOrFail($this->country_id);
        
        $this->states = $country->states()->orderBy('name')->select('id','name')->get();

       
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
        dd('Aqui vamos a guardar los datos');
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
        $this->rfc      = $record->rfc;
        $this->type     = $record->type;
        $this->address  = $record->address;
        $this->colony   = $record->colony;
        $this->references= $record->references;
        $this->zipcode   = $record->zipcode;
        $this->country_id= $record->country_id;
        $this->state_id  = $record->state_id;
        $this->municipality_id= $record->municipality_id;
        $this->city_id  = $record->city_id ;
        $this->notes    = $record->notes;
        $this->country_id = $record->country_id;
        $this->state_id     = $record->state_id;
        $this->municipality_id= $record->municipality_id;
        $this->city_id      = $record->city_id;
        $this->read_zipcode();
        $this->asign_colony();
        $this->showModel = true;
    }

    /**
     * Restaura variables
     * @return void
     */
    private function resetInputFields()
    {
        $this->reset('name','email','phone','rfc','type','address','colony','references','zipcode','notes');
        $this->reset('country_id','state_id','municipality_id','city_id');
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
        $this->delete($record);
    }

}
