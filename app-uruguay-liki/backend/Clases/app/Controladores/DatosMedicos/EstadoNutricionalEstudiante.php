<?php

namespace App\Controladores\DatosMedicos;
use Liki\Modelo;
use Liki\DelegateFunction;


class EstadoNutricionalEstudiante extends Modelo{
  public function __construct(){
    parent::__construct('estado_nutricional_estudiante');
  }
}
