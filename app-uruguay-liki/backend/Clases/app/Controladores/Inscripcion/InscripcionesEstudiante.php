<?php

namespace App\Controladores\Inscripcion;
use Liki\Modelo;
use Liki\DelegateFunction;


class InscripcionesEstudiante extends Modelo{
  public function __construct(){
    parent::__construct('inscripciones_estudiante');
  }
  
}
