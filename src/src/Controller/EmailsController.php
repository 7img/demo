<?php

namespace App\Controller;

use App\Models\BaseEmail;
use App\Repository\EmailsRepository;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Class EmailsController
 * @package App\Controller
 */
class EmailsController extends AbstractController
{
    protected EmailsRepository $emailsRepository;
    protected MailerInterface $mailer;

    /**
     * EmailsController constructor.
     * @param string $projectDir
     * @param MailerInterface $mailer
     */
    #[Pure] public function __construct(string $projectDir, MailerInterface $mailer)
    {
        $this->emailsRepository = new EmailsRepository($projectDir);
        $this->mailer = $mailer;
    }

    #[Route('/emails', name: 'emails')]
    public function index(): Response
    {
        $templates = $this->emailsRepository->getTemplates();

        return $this->json([
            'data' => [
                'templates' => $templates,
            ]
        ]);
    }

    #[Route('/emails/{template}', name: 'send', methods: ['POST'])]
    public function send(string $template): Response
    {
        $errors = array();

        // Check if the twig template exists.
        if (!$this->emailsRepository->isExistingTemplate($template)) {
            array_push($errors, 'template not found');
            return $this->JSONResponse(404, $errors);
        }

        // Attempt to get the model.
        try {
            /**
             * @var BaseEmail $emailTemplate
             */
            $emailTemplate = $this->emailsRepository->getModel($template);
        } catch (\Exception $exception) {
            array_push($errors, $exception->getMessage());
            return $this->JSONResponse(404, $errors);
        }

        $request = Request::createFromGlobals();

        // Set and validate the e-mail templated based on the request data.
        $emailTemplate->setViewData($request->toArray());
        $emailTemplate->validate();

        // Check for validation errors.
        if (!$emailTemplate->isValid()) {
            return $this->JSONResponse(400, $emailTemplate->getErrors());
        }

        // Attempt to send the email.
        try {
            $this->mailer->send($emailTemplate->build());
        } catch (\Exception $exception) {
            array_push($errors, $exception->getMessage());
            return $this->JSONResponse(500, $errors);
        }

        return $this->JSONResponse(200, array(), true);
    }

    /**
     * JSONResponse returns a predefined JSON response.
     * @param int $status
     * @param array $errors
     * @param bool $isSent
     * @return JsonResponse
     */
    private function JSONResponse(int $status, array $errors, bool $isSent = false): JsonResponse
    {
        return $this->json([
            'data' => [
                'isSent' => $isSent,
                'errors' => $errors
            ]
        ])->setStatusCode($status);
    }

}
