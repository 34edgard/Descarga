<?php

namespace App\Controladores\DatosExtra;
use Liki\Modelo;
use Liki\DelegateFunction;


class NivelInstruccion extends Modelo{
  public function __construct(){
    parent::__construct('nivel_instruccion');
  }
}
