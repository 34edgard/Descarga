<?php

namespace App\Controladores\Inscripcion;
use Liki\Modelo;
use Liki\DelegateFunction;


class Inscripciones extends Modelo{
  public function __construct(){
    parent::__construct('inscripciones');
  }
  
}