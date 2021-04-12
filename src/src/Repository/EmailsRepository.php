<?php

namespace App\Repository;

use Doctrine\Common\Collections\Collection;

/**
 * Class EmailsRepository
 * @package App\Repository
 */
class EmailsRepository
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * Checks if a template exists in the folder.
     * @param string $templateName
     * @return bool
     */
    public function isExistingTemplate(string $templateName): bool {
        $templates = $this->getTemplates();

        return in_array($templateName, $templates);
    }

    /**
     * Returns the templates from a folder.
     * @return array
     */
    public function getTemplates(): array
    {
        $regex = '/\.twig/m';
        $templates = array();
        $directoryFiles = array_values(scandir($this->projectDir.'/templates/emails'));

        foreach ($directoryFiles as $directoryFile) {
            if (preg_match($regex, $directoryFile)) {
                array_push($templates, preg_replace($regex, '', $directoryFile));
            }
        }

        return $templates;
    }

    /**
     * @param $template
     * @return mixed
     * @throws \Exception
     */
    public function getModel($template)
    {
        $class = '\App\\Models\\'.ucfirst($template);
        $model = new $class;

        return $model;
    }
}
