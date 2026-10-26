<?php

declare(strict_types=1);

// Importación de las dependencias necesarias
require_once 'clases.php';
require_once 'funciones.php';

echo "\n====================================================\n";
echo "=== SISTEMA DE LIQUIDACIÓN DE PERSONAL - ALCALDÍA ===\n";
echo "====================================================\n";

// Arreglo para almacenar el historial de todas las liquidaciones hechas en la sesión
$historialLiquidaciones = [];

// Bucle principal para que el sistema se mantenga activo
while (true) {
    echo "\nSeleccione el tipo de vinculación a registrar:\n";
    echo "1. Funcionario de Planta\n";
    echo "2. Contratista\n";
    echo "3. Salir (Ver resumen y cerrar)\n";
    $tipo = leer("Opción (1, 2 o 3): ");

    // 1. Condición para salir del sistema y mostrar el historial acumulado
    if ($tipo === '3') {
        echo "\n=====================================================\n";
        echo "========= RESUMEN FINAL DE LA SESIÓN (HISTORIAL) ======\n";
        echo "=====================================================\n";

        if (empty($historialLiquidaciones)) {
            echo "No se registraron liquidaciones durante esta sesión.\n";
        } else {
            foreach ($historialLiquidaciones as $index => $item) {
                echo "\nRegistro #" . ($index + 1) . "\n";
                echo $item['informacion'];
                echo "Neto a Pagar: $" . number_format($item['netoPagar'], 2, '.', ',') . "\n";
                echo "-----------------------------------------------------\n";
            }
        }

        echo "=====================================================\n";
        // Pausa final esperando a que el usuario presione Enter para salir
        leer("Presione [ENTER] para salir definitivamente del sistema...");
        exit(0);
    }

    // 2. Validación si la opción no es ninguna de las válidas
    if ($tipo !== '1' && $tipo !== '2') {
        echo "\n[ADVERTENCIA]: Opción no válida. Por favor seleccione 1, 2 o 3.\n";
        continue;
    }

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
        }

        $neto = $persona->liquidarSalario();
        $infoPersona = $persona->informacion();

        // Guardamos los datos de esta liquidación en el arreglo acumulador
        $historialLiquidaciones[] = [
            'informacion' => $infoPersona,
            'netoPagar' => $neto
        ];

        echo "\n=====================================================\n";
        echo "======== RESULTADO DEL PROCESO DE LIQUIDACIÓN ========\n";
        echo "======================================================\n";
        echo $infoPersona;
        echo "Neto a Pagar: $" . number_format($neto, 2, '.', ',') . "\n";
        echo "====================================================\n\n";

        // Pausa para esperar a que el usuario presione Enter antes de volver al menú
        leer("Presione [ENTER] para volver al menú principal...");

    } catch (Exception $e) {
        echo "\n[ERROR DE VALIDACIÓN]: " . $e->getMessage() . "\n";
        echo "Por favor intente registrar los datos nuevamente.\n\n";
        
        leer("Presione [ENTER] para continuar...");
    }
}