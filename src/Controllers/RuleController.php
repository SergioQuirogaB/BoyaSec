<?php

namespace Controllers;

use Models\Rule;

class RuleController
{
    private Rule $ruleModel;

    public function __construct()
    {
        $this->ruleModel = new Rule();
    }

    public function index(): array
    {
        return $this->ruleModel->all();
    }

    public function store(array $data): int
    {
        return $this->ruleModel->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->ruleModel->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->ruleModel->delete($id);
    }
}

