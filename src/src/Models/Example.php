<?php
namespace App\Models;

use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * Class Example
 * @package App\Models
 */
class Example extends BaseEmail
{
    protected string $subject = 'Example email';
    protected string $template = 'emails/example.twig';

    public function __construct()
    {
        $this->validationRules = new Assert\Collection([
            'firstName' => new Assert\Length(['min' => 3]),
            'lastName' => new Assert\Length(['min' => 3]),
            'email' => new Assert\Email(),
        ]);
    }

    public function build(): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from('starter@github.com')
            ->to(new Address($this->viewData['email']))
            ->subject($this->subject)
            // path of the Twig template to render
            ->htmlTemplate($this->template)
            // pass variables (name => value) to the template
            ->context([
                'name' => $this->viewData['firstName'] . ' ' . $this->viewData['lastName']
            ]);
    }

    // @TODO: mock function for a preview but not implemented yet.
    public function mock()
    {
        return array(
            'name' => [
                'firstName' => 'Lex',
                'lastName' => 'Ample',
            ],
            'email' => '@example.nl',
        );
    }


}
