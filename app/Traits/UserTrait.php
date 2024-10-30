<?php

namespace App\Traits;

trait UserTrait
{
    public function is($role){
        return $this->hasRole($role);
    }

    public function isSuperAdmin(){
        return $this->is('Super Admin');
    }
    public function isAdministrador(){
        return $this->is('Administrador');
    }

    public function isGerente(){
        return $this->is('Gerente');
    }
    public function isAsesor(){
        return $this->is('Asesor');
    }


    public function isVendedor(){
        return $this->is('Vendedor');
    }

    public function isCapturista(){

        return $this->is('Capturista') || $this->is('teller');
    }

    public function isProduccion(): bool{
        return $this->is('Producción');
    }

    public function isEnvios(): bool{
        return $this->is('Envíos');
    }

}
