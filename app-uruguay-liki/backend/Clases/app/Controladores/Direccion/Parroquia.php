<?php
namespace App\Controladores\Direccion;
use Liki\Modelo;
use Liki\DelegateFunction;


class Parroquia extends Modelo{
  public function __construct(){
    parent::__construct('parroquia');
  }
}