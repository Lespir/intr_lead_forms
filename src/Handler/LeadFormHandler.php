<?php
namespace App\Handler;

use App\Service\AmoCrmIntegrator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LeadFormHandler
{
    public function __construct(
        private AmoCrmIntegrator $amoIntegrator,
        private CsrfTokenManagerInterface $csrfTokenManager
    ) {}

    public function handle(
        Request $request,
        string $statusId,
        string $userGroup,
        string $csrfTokenId
    ): bool {
        if (!$request->isMethod('POST')) {
            return false;
        }

        $submittedToken = $request->request->get('_token');
        $token = new CsrfToken($csrfTokenId, $submittedToken);

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new AccessDeniedException('Неверный CSRF-токен');
        }

        $this->amoIntegrator->sendLead(
            $request->request->all(),
            $statusId,
            $userGroup
        );

        return true;
    }
}
