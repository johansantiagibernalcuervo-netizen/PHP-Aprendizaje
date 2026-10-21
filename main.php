<?php

declare(strict_types=1);

echo "\n====================================================\n";
echo "=== SISTEMA DE LIQUIDACIÓN DE PERSONAL - ALCALDÍA ===\n";
echo "====================================================\n";

echo "\nSeleccione el tipo de vinculación a registrar:\n";
echo "1. Funcionario de Planta\n";
echo "2. Contratista\n";
$tipo = leer("Opción (1 o 2): ");

try {
    $tipoDoc = leer("Ingrese el tipo de documento (CC, CE, NIT): ");
    $numDoc = leer("Número de documento: ");
    $nombres = leer("Nombres: ");
    $apellidos = leer("Apellidos: ");
    $correo = leer("Correo electrónico: ");

    if ($tipo === '1') {
        $salarioBasico = (float)leer("Salario básico mensual ($): ");
        $dias = (int)leer("Días trabajados en el mes (1-30): ");
        $auxTransporte = (float)leer("Valor Auxilio de transporte legal: ");

        $persona = new Funcionario($tipoDoc, $numDoc, $nombres, $apellidos, $correo, $salarioBasico, $dias, $auxTransporte);

    } elseif ($tipo === '2') {
        $honorarios = (float)leer("Honorarios mensuales pactados ($): ");
        $refuente = (float)leer("Porcentaje de retención en la fuente (%): ");

        $persona = new Contratista($tipoDoc, $numDoc, $nombres, $apellidos, $correo, $honorarios, $refuente);
    } else {
        throw new Exception("Opción de vinculación no válida.");
    }

    echo "\n=====================================================\n";
    echo "======== RESULTADO DEL PROCESO DE LIQUIDACIÓN ========\n";
    echo "======================================================\n";
    echo $persona->informacion();
    echo "Neto a Pagar: $" . number_format($persona->liquidarSalario(), 2, '.', ',') . "\n";
    echo "====================================================\n\n";
} catch (Exception $e) {
    echo "\n[ERROR DE VALIDACIÓN]: " . $e->getMessage() . "\n\n";
}