<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class Clients extends Component
{
    use WithPagination;
    public $pagination = 10;
    public $showModal= false;
    public $search;
 
    public function render()
    {
        return view('livewire.clients.clients',[
            'records' => $this->getClients(),
        ])->title(__('Clients'));;
    }

    public function getClients()
    {
        $qry  = Client::query();
        $qry = $qry->when($this->search, function ($query) {
            return $query->where('name', 'like', "%{$this->search}%")
                         ->orwhere('email', 'like', "%{$this->search}%")
                         ->orwhere('phone', 'like', "%{$this->search}%");
        });
 
        return  $qry->paginate($this->pagination);
 
    }

    public function store(){
        dd('Aqui vamos a guardar los datos');
    }
}
