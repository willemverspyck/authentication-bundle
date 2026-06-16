<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Service;

use Exception;
use Spyck\AuthenticationBundle\Entity\ModuleInterface;
use Spyck\AuthenticationBundle\Model\State;
use Spyck\AuthenticationBundle\Provider\ProviderInterface;
use Spyck\AuthenticationBundle\Repository\ModuleRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ModuleService
{
    private const string KEY = 'spyck:authentication:state';

    public function __construct(private readonly ModuleRepository $moduleRepository, private readonly RequestStack $requestStack)
    {
    }

    public function getModule(ProviderInterface $provider): ?ModuleInterface
    {
        $state = $this->getState();

        if (null === $state) {
            return null;
        }

        $module = $this->moduleRepository->getModuleById($state->getModule()->getId());

        if (null === $module) {
            throw new Exception('Module not found');
        }

        if ($state->getCode() === $provider->getCode()) {
            return $module;
        }

        throw new Exception('State is invalid');
    }

    public function setModule(ModuleInterface $module, string $code): void
    {
        $state = new State();
        $state->setModule($module);
        $state->setCode($code);

        $this->getSession()->set(self::KEY, serialize($state));
    }

    public function hasProviderSession(ProviderInterface $provider): bool
    {
        return $this->getSession()->has(self::KEY);
    }

    private function getState(): ?State
    {
        $session = $this->getSession();

        $state = $session->get(self::KEY);

        if (null === $state) {
            return null;
        }

        $session->remove(self::KEY);

        return unserialize($state);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
