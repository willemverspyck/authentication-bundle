<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Service;

use Spyck\AuthenticationBundle\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

readonly class ProviderService
{
    public function __construct(#[AutowireLocator(services: 'spyck.authentication.provider')] private ServiceLocator $serviceLocator)
    {
    }

    public function getProvider(string $code): ProviderInterface
    {
        $provider = array_find($this->getProviders(), fn (ProviderInterface $provider) => $provider->getCode() === $code);

        if (null === $provider) {
            throw new Exception(sprintf('Provider "%s" not found', $code));
        }

        return $provider;
    }

    /**
     * @return array<string, ProviderInterface>
     */
    private function getProviders(): array
    {
        return iterator_to_array($this->serviceLocator->getIterator());
    }
}
