<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Provider;

use Exception;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Spyck\AuthenticationBundle\Entity\ModuleInterface;
use Spyck\AuthenticationBundle\Service\ModuleService;
use Spyck\AuthenticationBundle\Service\ProviderService;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class UserProvider implements OAuthAwareUserProviderInterface
{
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker, private ModuleService $moduleService, private ProviderService $providerService, private TokenStorageInterface $tokenStorage)
    {
    }

    /**
     * @throws Exception
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response): ?UserInterface
    {
        $name = $response->getResourceOwner()->getName();

        $provider = $this->providerService->getProvider($name);

        $module = $this->getModule($provider);

        if (null !== $module) {
            $provider->authenticate($module, $response);

            return $this->tokenStorage->getToken()?->getUser();
        }

        if ($provider->isLogin()) {
            return $provider->login($response);
        }

        throw new Exception('Provider not found');
    }

    private function getModule(ProviderInterface $provider): ?ModuleInterface
    {
        $module = $this->moduleService->getModule($provider);

        if (null === $module) {
            return null;
        }

        if (false === $this->authorizationChecker->isGranted('ROLE_CONNECT')) {
            throw new AccessDeniedException();
        }

        return $module;
    }
}
