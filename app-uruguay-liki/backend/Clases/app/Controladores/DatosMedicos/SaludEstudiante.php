<?php

namespace App\Controladores\DatosMedicos;
use Liki\Modelo;
use Liki\DelegateFunction;



class SaludEstudiante extends Modelo{
  public function __construct(){
    parent::__construct('salud_estudiante');
  }
}