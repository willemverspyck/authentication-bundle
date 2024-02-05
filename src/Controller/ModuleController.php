<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Controller;

use Spyck\AuthenticationBundle\Model\Session;
use Spyck\AuthenticationBundle\Repository\ModuleRepository;
use Spyck\AuthenticationBundle\Service\ModuleService;
use Spyck\AuthenticationBundle\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
final class ModuleController extends AbstractController
{
    #[Route(path: '/module/{moduleId}/{name}', name: 'spyck_authentication_module_default', requirements: ['moduleId' => Requirement::DIGITS, 'name' => Requirement::ASCII_SLUG])]
    public function default(ModuleRepository $moduleRepository, ModuleService $moduleService, int $moduleId, string $name): RedirectResponse
    {
        if (false === $this->isGranted('ROLE_CONNECT')) {
            throw $this->createAccessDeniedException();
        }

        $module = $moduleRepository->getModuleById($moduleId);

        if (null === $module) {
            throw $this->createNotFoundException('Module not found');
        }

        $moduleService->setModule($module, $name);

        return $this->redirectToRoute('hwi_oauth_service_redirect', ['service' => $name]);
    }
}
