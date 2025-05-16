<?php

namespace App\Service;

class AmoCrmIntegrator
{
    public function __construct(
        private string $projectDir,
        private string $defaultLeadName = 'Заявка с сайта AONik'
    ) {}

    public function sendLead(array $data, string $statusId, string $userGroup): void
    {
        $_POST = array_merge($data, [
            'status' => $statusId,
            'intr_group' => $userGroup,
            'nameOfLead' => $this->defaultLeadName
        ]);

        require $this->projectDir.'/public/introvert_save.php';
    }
}
