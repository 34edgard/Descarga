<?php
namespace App\Controladores\Direccion;
use Liki\Modelo;
use Liki\DelegateFunction;


class Estado extends Modelo{
  public function __construct(){
    parent::__construct('estado');
  }
}