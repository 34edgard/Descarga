<?php
namespace Liki;

class DelegateFunction{
    public static function exec($name = '',$parametros=[],$funcionesExtra =[]){
       $class = include CONTOLLER_PATH.'Manejadores/'.$name.'.php';
      return  $class::run($parametros,$funcionesExtra);    
    }   
    public static function loadData($name = ''){
         return include CONTOLLER_PATH.'/'.$name.'.php';
    }
}