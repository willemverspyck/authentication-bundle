<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Service;

use Exception;
use Spyck\AuthenticationBundle\Entity\ModuleInterface;
use Spyck\AuthenticationBundle\Model\Session;
use Spyck\AuthenticationBundle\Provider\ProviderInterface;
use Spyck\AuthenticationBundle\Repository\ModuleRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ModuleService
{
    private const string KEY = 'spyck:authentication';

    public function __construct(private readonly ModuleRepository $moduleRepository, private readonly RequestStack $requestStack)
    {
    }

    public function getModule(ProviderInterface $provider): ?ModuleInterface
    {
        $providerSession = $this->getProviderSession();

        if (null === $providerSession) {
            return null;
        }

        $module = $this->moduleRepository->getModuleById($providerSession->getModule()->getId());

        if (null === $module) {
            throw new Exception('Module not found');
        }

        if ($providerSession->getName() === $provider->getName()) {
            return $module;
        }

        throw new Exception('Session is invalid');
    }

    public function setModule(ModuleInterface $module, string $name): void
    {
        $session = $this->getSession();

        $providerSession = new Session();
        $providerSession->setModule($module);
        $providerSession->setName($name);

        $session->set(self::KEY, serialize($providerSession));
    }

    public function hasProviderSession(ProviderInterface $provider): bool
    {
        return $this->getSession()->has(self::KEY);
    }

    private function getProviderSession(): ?Session
    {
        $session = $this->getSession();

        $providerSession = $session->get(self::KEY);

        if (null === $providerSession) {
            return null;
        }

        $session->remove(self::KEY);

        return unserialize($providerSession);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
