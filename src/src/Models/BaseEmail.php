<?php

namespace App\Models;

use Doctrine\ORM\Query\Expr\Base;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * Class BaseEmail
 * @package App\Models
 */
abstract class BaseEmail
{
    // The validation rules should be specified in the extended model.
    protected Assert\Collection $validationRules;

    /**
     * @var boolean $isValid
     */
    private bool $isValid = false;
    /**
     * @var array $viewData
     */
    protected array $viewData = [];
    /**
     * @var array $errors
     */
    protected array $errors = [];
    /**
     * @var string $subject
     */
    protected string $subject;
    /**
     * @var string $template
     */
    protected string $template;

    /**
     * validate validates the viewData
     * @param array $input
     */
    public function validate(): void
    {
        $validator = Validation::createValidator();
        $groups = new Assert\GroupSequence(['Default', 'custom']);

        $violations = $validator->validate($this->viewData, $this->validationRules, $groups);
        $violationCount = $violations->count();

        $this->isValid = $violationCount == 0;

        if (!$this->isValid) {
            $errorIndex = 0;

            while($errorIndex <= $violationCount -1) {
                $violation =  $violations->get($errorIndex);

                // Push errors into the error array.
                array_push($this->errors, $violation->getMessage());
                $errorIndex++;
            }
        }
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param array $viewData
     */
    public function setViewData(array $viewData): void
    {
        $this->viewData = $viewData;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Overwrite this with your build function
     * @param array $input
     */
    abstract public function build(): TemplatedEmail;

    /**
     * Overwrite this with your mock data.
     */
    abstract public function mock();
}
