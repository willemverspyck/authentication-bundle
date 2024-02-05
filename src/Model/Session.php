<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Model;

use Spyck\AuthenticationBundle\Entity\ModuleInterface;

final class Session
{
    private ModuleInterface $module;
    private string $name;

    public function setModule(ModuleInterface $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getModule(): ModuleInterface
    {
        return $this->module;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
