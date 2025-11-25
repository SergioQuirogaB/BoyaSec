<?php

namespace Services;

use Models\Alert;

class AlertService
{
    private Alert $alertModel;

    public function __construct()
    {
        $this->alertModel = new Alert();
    }

    public function create(array $data): int
    {
        return $this->alertModel->create($data);
    }

    /**
     * Punto de extensión para exportar reportes en PDF. La lógica real
     * puede integrarse posteriormente usando una librería como TCPDF.
     */
    public function preparePdfReport(array $filters = []): void
    {
        // TODO: Implementar exportación PDF.
    }
}

