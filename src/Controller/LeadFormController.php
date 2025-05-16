<?php
namespace App\Controller;

use App\Handler\LeadFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeadFormController extends AbstractController
{
    public function __construct(
        private LeadFormHandler $formHandler
    ) {}

    #[Route('/form/lead1', name: 'lead_form1')]
    public function leadForm1(Request $request): Response
    {
        return $this->handleFormRequest(
            $request,
            '24374824',
            'ske1@gmail.com;formozafiti@gmail.com;bbb@bb.ru',
            'lead/form1.html.twig',
            'form1'
        );
    }

    #[Route('/form/lead2', name: 'lead_form2')]
    public function leadForm2(Request $request): Response
    {
        return $this->handleFormRequest(
            $request,
            '24374821',
            'olgamooha2212@mail.com;aleshak97@mail.ru;deemird2@yandex.ru',
            'lead/form2.html.twig',
            'form2'
        );
    }

    #[Route('/form/success', name: 'form_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('lead/success.html.twig', [
            'form1_url' => $this->generateUrl('lead_form1'),
            'form2_url' => $this->generateUrl('lead_form2')
        ]);
    }

    private function handleFormRequest(
        Request $request,
        string $statusId,
        string $userGroup,
        string $template,
        string $csrfTokenId
    ): Response {
        try {
            if ($this->formHandler->handle($request, $statusId, $userGroup, $csrfTokenId)) {
                return $this->redirectToRoute('form_success');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->render($template, [
            'csrf_token' => $this->container->get('security.csrf.token_manager')->getToken($csrfTokenId)
        ]);
    }
}


