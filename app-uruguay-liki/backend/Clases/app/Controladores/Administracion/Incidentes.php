<?php

namespace App\Controladores\Administracion;
use Liki\Modelo;
use Liki\DelegateFunction;


class Incidentes extends Modelo{
  
  public function __construct(){
    parent::__construct('incidentes');
  }
  
}