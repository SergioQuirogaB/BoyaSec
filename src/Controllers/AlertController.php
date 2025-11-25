<?php

namespace Controllers;

use Models\Alert;

class AlertController
{
    private Alert $alertModel;

    public function __construct()
    {
        $this->alertModel = new Alert();
    }

    public function latest(int $limit = 10): array
    {
        return $this->alertModel->latest($limit);
    }

    public function all(int $limit = 100): array
    {
        return $this->alertModel->all($limit);
    }
}

