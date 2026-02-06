<?php

namespace App\Controladores\DatosMedicos;
use Liki\Modelo;
use Liki\DelegateFunction;


class Tratamiento extends Modelo{
  public function __construct(){
    parent::__construct('tratamiento');
  }
}