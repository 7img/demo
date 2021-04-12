<?php


namespace App\Repository;

// @TODO: implement interface
interface EmailRepositoryInterface {
    public function isExistingTemplate(string $templateName): bool;
    public function getTemplates(): array;
    public function getModel($template);
}
