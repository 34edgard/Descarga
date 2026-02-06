<?php

namespace App\Controladores\Administracion;
use Liki\Modelo;
use Liki\DelegateFunction;


class Pagos extends Modelo{
  
  public function __construct(){
    parent::__construct('pagos');
  }
  
}