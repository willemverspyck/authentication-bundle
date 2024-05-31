<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Service;

use Countable;
use Exception;
use IteratorAggregate;
use Spyck\AuthenticationBundle\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ProviderService
{
    /**
     * @param Countable&IteratorAggregate $providers
     */
    public function __construct(#[AutowireIterator(tag: 'spyck.authentication.provider', defaultIndexMethod: 'getName')] private iterable $providers)
    {
    }

    /**
     * @throws Exception
     */
    public function getProvider(string $name): ProviderInterface
    {
        foreach ($this->providers->getIterator() as $index => $provider) {
            if ($index === $name) {
                return $provider;
            }
        }

        throw new Exception(sprintf('Provider "%s" not found', $name));
    }
}
