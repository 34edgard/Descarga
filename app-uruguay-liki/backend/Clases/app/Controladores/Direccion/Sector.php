<?php
namespace App\Controladores\Direccion;
use Liki\Modelo;
use Liki\DelegateFunction;


class Sector extends Modelo{
  public function __construct(){
    parent::__construct('sector');
  }
}