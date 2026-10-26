<?php

declare(strict_types=1);

/**
 * Clase Abstracta Persona (Clase Padre)
 * Núcleo base que define la estructura común y encapsulamiento para cualquier tipo de trabajador.
 */
abstract class Persona 
{
    protected string $tipoDocumento;
    protected string $numeroDocumento;
    protected string $nombres;
    protected string $apellidos;
    protected string $correo;

    public function __construct(
        string $tipoDocumento, 
        string $numeroDocumento, 
        string $nombres, 
        string $apellidos, 
        string $correo
    ) {
        $this->setTipoDocumento($tipoDocumento);
        $this->setNumeroDocumento($numeroDocumento);
        $this->setNombres($nombres);
        $this->setApellidos($apellidos);
        $this->setCorreo($correo);
    }

    // --- GETTERS y SETTERS (Encapsulamiento) ---

    public function getTipoDocumento(): string 
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(string $tipoDocumento): void 
    {
        $this->tipoDocumento = strtoupper(trim($tipoDocumento));
    }

    public function getNumeroDocumento(): string 
    {
        return $this->numeroDocumento;
    }

    public function setNumeroDocumento(string $numeroDocumento): void 
    {
        $this->numeroDocumento = trim($numeroDocumento);
    }

    public function getNombres(): string 
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): void 
    {
        $this->nombres = ucwords(strtoupper(trim($nombres)));
    }

    public function getApellidos(): string 
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void 
    {
        $this->apellidos = ucwords(strtoupper(trim($apellidos)));
    }

    public function getCorreo(): string 
    {
        return $this->correo;
    }

    /**
     * Valida obligatoriamente que el correo electrónico tenga un formato real.
     */
    public function setCorreo(string $correo): void 
    {
        if(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("El formato del correo electrónico no es válido.");
        }
        $this->correo = trim($correo);
    }

    // Método abstracto obligatorio para las clases hijas
    abstract public function informacion(): string;
    abstract public function liquidarSalario(): float;
}

/**
 * Clase Funcionario (Hereda de Persona) - Empleado Público
 * Gestiona la lógica de empleados de planta con salario básico y auxilio de transporte.
 */
class Funcionario extends Persona
{
    private float $salarioBasico;
    private int $diasTrabajados;
    private float $auxilioTransporteLegal;

    public function __construct(
        string $tipoDocumento,
        string $numeroDocumento,
        string $nombres,
        string $apellidos,
        string $correo,
        float $salarioBasico,
        int $diasTrabajados,
        float $auxilioTransporteLegal
    ) {
        parent::__construct($tipoDocumento, $numeroDocumento, $nombres, $apellidos, $correo);
        $this->setSalarioBasico($salarioBasico);
        $this->setDiasTrabajados($diasTrabajados);
        $this->setAuxilioTransporteLegal($auxilioTransporteLegal);
    }

    public function getSalarioBasico(): float
    {
        return $this->salarioBasico;
    }

    public function setSalarioBasico(float $salarioBasico): void
    {
        if ($salarioBasico < 0) {
            throw new InvalidArgumentException("El salario básico no puede ser negativo.");
        }
        $this->salarioBasico = $salarioBasico;
    }

    public function getDiasTrabajados(): int
    {
        return $this->diasTrabajados;
    }

    /**
     * Valida que los días laborados correspondan a un mes calendario válido (1 a 30).
     */
    public function setDiasTrabajados(int $diasTrabajados): void
    {
        if ($diasTrabajados < 1 || $diasTrabajados > 30) {
            throw new InvalidArgumentException("Los días trabajados deben estar entre 1 y 30.");
        }
        $this->diasTrabajados = $diasTrabajados;
    }

    public function getAuxilioTransporteLegal(): float
    {
        return $this->auxilioTransporteLegal;
    }

    public function setAuxilioTransporteLegal(float $auxilioTransporteLegal): void
    {
        $this->auxilioTransporteLegal = $auxilioTransporteLegal;
    }

    /**
     * Funcionalidad 1: Información detallada del Funcionario
     */
    public function informacion(): string
    {
        return sprintf(
            "--- FUNCIONARIO DE PLANTA ---\n" .
            "Documento: %s %s\n" .
            "Nombre Completo: %s %s\n" .
            "Correo: %s\n" .
            "Salario Básico Mensual: $%.2f\n" .
            "Días Laborados: %d\n",
            $this->tipoDocumento,
            $this->numeroDocumento,
            $this->nombres,
            $this->apellidos,
            $this->correo,
            $this->salarioBasico,
            $this->diasTrabajados
        );
    }

    /**
     * Funcionalidad 2: Liquidación de Salario
     * (Normativa colombiana: Devengado por días - Salud 4% - Pensión 4%)
     */
    public function liquidarSalario(): float
    {
        $salarioProporcional = ($this->salarioBasico / 30) * $this->diasTrabajados;

        // Auxilio de transporte proporcional si aplica (ejemplo simplificado)
        $auxilioProporcional = ($this->auxilioTransporteLegal / 30) * $this->diasTrabajados;

        // Deducciones obligatorias de ley en Colombia (Salud 4% y Pensión 4% del básico devengado)
        $salud = $salarioProporcional * 0.04;
        $pension = $salarioProporcional * 0.04;

        $netoPagar = ($salarioProporcional + $auxilioProporcional) - ($salud + $pension);
        return round($netoPagar, 2);
    }
}

/**
 * Clase Contratista (Hereda de Persona) - Prestación de Servicios
 * Gestiona la lógica de contratistas independientes por honorarios.
 */
class Contratista extends Persona
{
    private float $honorariosMensuales;
    private float $porcentajeRetencionFuente; // Porcentaje de retención en la fuente

    public function __construct(
        string $tipoDocumento, 
        string $numeroDocumento, 
        string $nombres, 
        string $apellidos, 
        string $correo,
        float $honorariosMensuales,
        float $porcentajeRetencionFuente
    ) {
        parent::__construct($tipoDocumento, $numeroDocumento, $nombres, $apellidos, $correo);
        $this->setHonorariosMensuales($honorariosMensuales);
        $this->setPorcentajeRetencionFuente($porcentajeRetencionFuente);
    }

    public function getHonorariosMensuales(): float
    {
        return $this->honorariosMensuales;
    }

    public function setHonorariosMensuales(float $honorariosMensuales): void
    {
        if ($honorariosMensuales < 0) {
            throw new InvalidArgumentException("Los honorarios mensuales no pueden ser negativos.");
        }
        $this->honorariosMensuales = $honorariosMensuales;
    }

    public function getPorcentajeRetencionFuente(): float
    {
        return $this->porcentajeRetencionFuente;
    }

    public function setPorcentajeRetencionFuente(float $porcentajeRetencionFuente): void
    {
        $this->porcentajeRetencionFuente = $porcentajeRetencionFuente;
    }

    /**
     * Funcionalidad 1: Información detallada del Contratista
     */
    public function informacion(): string
    {
        return sprintf(
            "--- CONTRATISTA (PRESTACIÓN DE SERVICIOS) ---\n" .
            "Documento: %s %s\n" .
            "Nombre Completo: %s %s\n" .
            "Correo: %s\n" .
            "Honorarios Mensuales: $%.2f\n" .
            "Retención en la Fuente: %.1f%%\n",
            $this->tipoDocumento,
            $this->numeroDocumento,
            $this->nombres,
            $this->apellidos,
            $this->correo,
            $this->honorariosMensuales,
            $this->porcentajeRetencionFuente
        );
    }

    /**
     * Funcionalidad 2: Liquidación de Honorarios (Deducción de Retención en la Fuente)
     */
    public function liquidarSalario(): float
    {
        // En contratistas, el neto a girar corresponde a los honorarios menos la retención en la fuente
        $retencion = $this->honorariosMensuales * ($this->porcentajeRetencionFuente / 100);
        $netoPagar = $this->honorariosMensuales - $retencion;
        return round($netoPagar, 2);
    }
}
