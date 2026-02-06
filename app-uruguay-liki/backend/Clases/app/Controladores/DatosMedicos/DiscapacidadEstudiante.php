<?php

namespace App\Controladores\DatosMedicos;
use Liki\Modelo;
use Liki\DelegateFunction;


class DiscapacidadEstudiante extends Modelo{
  public function __construct(){
    parent::__construct('discapacidad_estudiante');
  }
} 