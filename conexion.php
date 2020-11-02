<?php

function retornarConexion()
{
    $server = "localhost";
    $usuario = "root";
    $clave = "";
    $dbase = "base1";

    $con = mysqli_connect($server, $usuario, $clave, $dbase) or die("problemas");
    mysqli_set_charset($con, 'utf8');
    return $con;
}
