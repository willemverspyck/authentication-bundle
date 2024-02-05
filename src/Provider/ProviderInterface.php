<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Provider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Spyck\AuthenticationBundle\Entity\ModuleInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Security\Core\User\UserInterface;

#[Autoconfigure(tags: ['spyck.authentication.provider'])]
interface ProviderInterface
{
    public static function getName(): string;

    public function isLogin(): bool;

    public function login(UserResponseInterface $userResponse): UserInterface;

    public function authenticate(ModuleInterface $module, UserResponseInterface $userResponse): void;
}
