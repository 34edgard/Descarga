<?php

namespace App\Controladores\Administracion;
use Liki\Modelo;
use Liki\DelegateFunction;


class ConceptosPago extends Modelo{
  
  public function __construct(){
    parent::__construct('conceptos_pago');
  }
  
}