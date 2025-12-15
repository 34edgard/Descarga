# Liki

## ¿Que es LIKI?

liki es un Framework de php, minimalista y ligero, creado para desarrollar aplicaciones web, crear api-rest, 
proyectos academicos, desarrollo de NVP (producto minimo viable).

## Enfoque de Liki

Liki esta enfocado en personas que recien empiezan en el desarrollo web, como estudiantes, desarrolladores que trabajan en movil,
gente que quiera desarrollar rapido o provar conceptos para su aplicacion web sin tener que usar framework mas complejos y pesados como
laravel o que esten trabajando en un entorno con recursos limitados como servidores compartidos.


## Lo que ofrece Liki 

liki ofrece un marco de trabajo basicas para el desarrollo rapido de pequeñas web, mvp o api-rest, muy similar a framework como 
laravel, pero en un tamaño mas reducido y sin dependecias externas


## Características principales:

- Sistema de plantillas basado en componentes
- pagina.php:36-40
- Manejo de errores integrado ErrorHandler.php:8-26
- Sistema de sesiones Sesion.php:61-103
- ORM simple para base de datos Usuario.php:6-9

## Requisitos:
PHP v7.4 (mínima) o PHP v8.2.0 o mayor (recomendada)

## Estructura del proyecto:
Explicar brevemente frontend/ y backend/

## Ejemplo de uso básico: 
Un "Hello World" o componente simple

 para hacer un "hello world" simple en el index.php se debe crear una ruta

>Ruta::get('/HelloWorld',
function(){
  echo 'Hello World';
});


después se debe abrir en el navegador la ruta 'http/localhost/HelloWorld'





