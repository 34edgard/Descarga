<?php

namespace App\Controladores\Personas;
use Liki\Modelo;
use Liki\DelegateFunction;

class DocenteSeccion extends Modelo{
  public function __construct(){
    parent::__construct('docente_seccion');
  }
}

