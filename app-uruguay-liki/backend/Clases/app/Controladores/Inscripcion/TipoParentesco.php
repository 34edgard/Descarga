<?php

namespace App\Controladores\Inscripcion;
use Liki\Modelo;
use Liki\DelegateFunction;



class TipoParentesco extends Modelo{
  
  public function __construct(){
    parent::__construct('tipo_parentesco');
  }
}
