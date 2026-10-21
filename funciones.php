<?php
/**
 * --- INTERFAZ DE CONSOLA INTERACTIVA ---
 */
function leer(string $mensaje): string
{
    echo $mensaje;
    $resultado = readline();
    return trim($resultado !== false ? $resultado : '');
}