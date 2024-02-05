<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Provider;

use Exception;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Spyck\AuthenticationBundle\Service\ModuleService;
use Spyck\AuthenticationBundle\Entity\ModuleInterface;
use Spyck\AuthenticationBundle\Repository\ModuleRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;

abstract class AbstractProvider implements ProviderInterface
{
    private ModuleRepository $moduleRepository;
    private ModuleService $moduleService;

    #[Required]
    public function setModuleRepository(ModuleRepository $moduleRepository): void
    {
        $this->moduleRepository = $moduleRepository;
    }

    #[Required]
    public function setModuleService(ModuleService $moduleService): void
    {
        $this->moduleService = $moduleService;
    }

    public function isLogin(): bool
    {
        return false;
    }

    public function login(UserResponseInterface $userResponse): UserInterface
    {
        throw new MethodNotImplementedException(__FUNCTION__);
    }

    public function authenticate(ModuleInterface $module, UserResponseInterface $userResponse): void
    {
        throw new MethodNotImplementedException(__FUNCTION__);
    }

    protected function patchModule(ModuleInterface $module, array $parameters): void
    {
        $this->moduleRepository->patchModule(module: $module, fields: ['parameters'], parameters: $parameters);
    }
}
