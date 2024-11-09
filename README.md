<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Arranque

Se necesitará lo siguiente para poder arrancar el proyecto:

<ul>
<li>Node v22.11.0</li>
<li>NPM v10.9.0</li>
<li>composer 2.8.2</li>
<li>php 8.3.2</li>
</ul>

Una vez se tengan estos, el orden de arranque será el siguiente.
<ol>
<li>Clonación del proyecto</li>
<li>Instalación de paquetes: composer install</li>
<li>Instalación de dependencias: npm install</li>
<li>Arranque de modo dev: npm run dev</li>
<li>Arranque servidor: php artisan serve</li>
</ol>

Nota: En caso de ser necesario pueden ejecutar por URL /clear-cache para limpiar los datos almacenados y regenerar las
caches del sistema.

## Inicio sesión

Para poder iniciar sesión en el sistema debe ingresar su correo y contraseña registrados anteriormente en el sistema. En
caso de no desear registrarse puede usar la cuenta administradora.

<p>Usuario: admin@example.com</p> 
<p>Contraseña:demo</p>

Nota: Todas las contraseñas son demo para optimizar tiempos.

## Desarrollo

El desarrollo consta de un funcionamiento de panel básico, se aplica laravel/ui en vez de breeze ya que disponemos de
poco tiempo para desarrollar el software.

Una vez entras al sistema te encuentras con un login el cual se especifica arriba como acceder a el, una vez accedido
podremos ingresar un DNI para gestionar una cita para un usuario o en caso de no existir un usuario con ese DNI darlo de
alta y solicitar la cita. Disponemos de envío por correo con un sistema de desarrollo Mailtrap el cual se deberá
configurar en el archivo .env con los datos figurantes en el propio mailtrap (cualquier duda preguntar al respecto y se
os adjunta una cuenta con los datos puestos). Al finalizar la solicitud podremos acceder a el Listado de Citas para ver
las citas que disponemos y filtrar por ellas. En caso del usuario Administrador puede ver todas las citas del sistema,
para el resto de usuarios deberan acceder al sistema con su correo y contraseña (demo) para ver su listado de citas.

## Ejercicio 1: Descodificación

Para poder acceder a ver este ejercicio es necesario primero iniciar sesión y posteriormente a esto acceder por el menú
contextual del sistema o por el siguiente enlace.

La información respecto a este ejercicio se encuentra en las siguientes carpetas del proyecto:

<ul>
<li>app/Http/Controllers/DecodificacionController.php</li>
<li>web.php</li>
</ul>

<p>
Enlace: 
<a href="http://127.0.0.1:8000/decodificacion">Ejercicio Descodificación</a>
</p>

## About

<p>Software para prueba de acceso.</p>
<p>Desarrollado para la empresa: Pandorafms </p>
<p>Desarrollador: David Fernandez Roman -
39498123Q</p>
