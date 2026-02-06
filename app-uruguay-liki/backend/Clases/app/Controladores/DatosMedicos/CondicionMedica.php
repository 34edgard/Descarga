<?php

namespace App\Controladores\DatosMedicos;
use Liki\Modelo;
use Liki\DelegateFunction;



class CondicionMedica extends Modelo{
  public function __construct(){
    parent::__construct('condicion_medica');
  }
}