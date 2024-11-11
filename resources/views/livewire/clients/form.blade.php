<x-confirmation-modal wire:modelwire:model.live="showModal" maxWidth="5xl">

    <x-slot name="title">
        <div class="text-2xl font-bold text-center">
            {{ __('New Client') }}
        </div>
    </x-slot>
    <x-slot name="content">
        <div class="w-full">
            <div class="grid grid-cols-2 gap-4 p-4">
                 <!-- Columna Izquierda -->
                <div class="space-y-4 w-full p-4 border-2 border-black">
                    <!-- Nombre -->
                    <div class="flex gap-2 text-justify items-center">
                        <label class="bg-gray-100">{{ __('Name') }}</label>
                        <input type="text" 
                                wire:model="name" 
                                class="w-full rounded-md border border-gray-300"
                                required
                                placeholder="{{ __('Name') }}">
                    </div>
                    <!-- Correo -->
                    <div class="flex gap-2 text-justify items-center">
                        <label class="bg-gray-100">{{ __('Email') }}</label>
                        <input type="text" 
                                wire:model="email" 
                                class="w-full rounded-md border border-gray-300"
                                required
                                placeholder="{{('Email')}}">
                    </div>

                    <!-- Teléfono -->
                    <div class="flex gap-2 text-justify items-center">
                        <label class="bg-gray-100">{{ __('Phone') }}</label>
                        <input type="text" 
                                wire:model="phone"
                                pattern="[0-9]" 
                                maxlength="15"
                                class="w-full rounded-md border border-gray-300" 
                                required
                                placeholder="{{__('Phone')}}">

                    </div>

                    <!-- Celuar -->
                    <div class="flex gap-2 text-justify items-center">
                        <label class="bg-gray-100">{{ __('Mobile') }}</label>
                        <input type="text" 
                                wire:model="mobile"
                                pattern="[0-9]" 
                                maxlength="15"
                                class="w-full rounded-md border border-gray-300" 
                                placeholder="{{__('Mobile')}}">
                    </div>

                    <!-- Curp -->
                    <div class="flex gap-2 text-justify items-center">
                        <label class="bg-gray-100">{{ __('Curp') }}</label>
                        <input type="text" wire:model="curp" class="w-full rounded-md border border-gray-300" placeholder="{{__('Curp')}}">
                    
                    </div>

                    <!-- Ine -->
                    <div class="flex gap-2 text-justify items-center">
                        <label class="bg-gray-100">{{ __('Ine') }}</label>
                        <input type="text" wire:model="ine" class="w-full rounded-md border border-gray-300" placeholder="{{__('Ine')}}">
                    </div>

                    <!-- Rfc -->
                    <div class="flex gap-2 text-justify items-center">
                        <label class="bg-gray-100">{{ __('Rfc') }}</label>
                        <input type="text" wire:model="rfc" class="w-full rounded-md border border-gray-300" placeholder="{{__('Rfc')}}">
                    </div> 

                    <!-- Tipo de Persona-->
                    <div class="flex gap-2 w-full"> 
                        <label for="type">{{__('Type')}}</label>
                        <select wire:model="type"
                                class="w-full rounded-md border border-gray-300">
                            <option value="">{{(__('Type'))}}</option>
                            <option value="Física">{{(__('Física'))}}</option>
                            <option value="Moral">{{(__('Moral'))}}</option>
                        </select>
                    </div>  

                    <!-- Notas -->
                    <div class="text-center w-full flex flex-col"> 
                        <label class="border-gray-300">{{__('Notes')}}</label>
                        <textarea wire:model="notes" cols="50" class="w-full"></textarea>
                    </div>      
                    {{-- <div class="flex gap-2 w-full"> 
                        <label for="type">{{__('Notes')}}</label>
                        <textarea wire:model="notes" cols="50"></textarea>
                    </div>             --}}

                </div>
            
                <!-- Columna Derecha -->
                <div class="space-y-4 w-full p-4 border-2 border-black">

                        <!-- Código -->
                        <div class="flex gap-2 w-full">
                            <label class="bg-gray-100">{{ __('Zipcode') }}</label>
                            <input type="text" 
                                    wire:model.live="zipcode" 
                                    wire:keypress="read_zipcode"
                                    maxlength="5"
                                    pattern="[0-9]"
                                    class="w-auto rounded-md border border-gray-300" 
                                    placeholder="{{__('Zipcode')}}">
                        </div>

                        <!-- Países -->
                        <div class="flex gap-2">
                            <label class=" border-gray-300">{{__('Country')}}</label>
                            <select wire:model="country_id" class="w-full rounded-md border border-gray-300">
                                <option value="">{{(__('Select'))}}</option>
                                @foreach ($countries as $countryName => $countryId)
                                    <option value="{{ $countryId }}" {{ $country_id == $countryId ? 'selected' : ''}}>{{ $countryName }}</option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Entidad Federativa -->
                        <div class="flex gap-2 w-full"> 
                            <label class="w-auto  border-gray-300">{{__('State')}}</label>
                            <select wire:model="state_id" 
                                    wire:change="read_municipalities"
                                    class="w-full rounded-md border border-gray-300">
                                <option value="">{{(__('Select'))}}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Municipios -->
                        <div class="flex gap-2 w-full"> 
                            <label class="border-gray-300">{{__('Municipality')}}</label>
                            <select wire:model="municipality_id" 
                                    wire:change="read_cities"
                                    class="w-full rounded-md border border-gray-300"
                                    {{isset($municipalities) && $municipalities->count() ? '' : 'disabled'}}>
                                <option value="">{{(__('Select'))}}</option>
                                @if(isset($municipalities) && $municipalities->count())
                                    @foreach ($municipalities as $municipality)
                                        <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Ciudades -->
                        <div class="flex gap-2 w-full"> 
                            <label class=" border-gray-300">{{__('City')}}</label>
                            <select wire:model="city_id" 
                                    wire:change="read_colonies"
                                    class="w-full rounded-md border border-gray-300"
                                    {{isset($cities) && $cities->count() ? '' : 'disabled'}}>
                                <option value="">{{(__('Select'))}}</option>
                                @if(isset($cities) && $cities->count())
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Colonias -->
                        <div class="flex gap-2 w-full"> 
                            <label class="border-gray-300">{{__('Colony')}}</label>
                            <select wire:model="colony_id" 
                                    wire:change="asign_colony"
                                    class="w-full rounded-md border border-gray-300"
                                    {{isset($colonies) && $colonies->count() ? '' : 'disabled'}}>
                                <option value="">{{(__('Select'))}}</option>
                                @if(isset($colonies) && $colonies->count())
                                    @foreach ($colonies as $colonyselect)
                                        <option value="{{ $colonyselect->id }}">{{ $colonyselect->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>   
                        
                        <!-- Colonia -->
                        <div class="flex gap-2 text-justify items-center">
                            <label class="bg-gray-100">{{ __('Colony') }}</label>
                            <input type="text" wire:model="colony" class="w-full rounded-md border border-gray-300" placeholder="{{__('Colony')}}">
                        </div> 

                        <!-- Dirección -->
                        <div class="flex gap-2 text-justify items-center">
                            <label class="bg-gray-100">{{ __('Address') }}</label>
                            <input type="text" 
                                    wire:model="address" 
                                    class="w-full rounded-md border border-gray-300" 
                                    placeholder="{{__('Full Address')}}">
                        </div> 

                        <!-- Referencias -->
                        <div class="text-center w-full flex flex-col"> 
                            <label class="border-gray-300">{{__('References')}}</label>
                            <textarea wire:model="references" cols="50" class="w-full"></textarea>
                        </div>    
                      
                </div>
            </div>
        </div>

  

    
       {{-- @include('livewire.clients.form_content') --}}

    </x-slot>

    <x-slot name="footer">

        <x-danger-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-danger-button>

        <x-button class="ms-3 bg-green-400 hover:bg-green-800" wire:click="store" wire:loading.attr="disabled">
            <div></div>
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-confirmation-modal>