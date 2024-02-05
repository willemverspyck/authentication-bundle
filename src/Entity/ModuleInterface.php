<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Entity;

interface ModuleInterface
{
    public function getId(): ?int;

    public function getParameters(): array;

    public function setParameters(array $parameters): self;
}
