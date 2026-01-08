<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Service;

use Spyck\AuthenticationBundle\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

readonly class ProviderService
{
    public function __construct(#[AutowireLocator(services: 'spyck.authentication.provider', defaultIndexMethod: 'getName')] private ServiceLocator $serviceLocator)
    {
    }

    public function getProvider(string $name): ProviderInterface
    {
        return $this->serviceLocator->get($name);
    }
}
