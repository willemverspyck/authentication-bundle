<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Model;

use Spyck\AuthenticationBundle\Entity\ModuleInterface;

final class State
{
    private ModuleInterface $module;
    private string $code;

    public function setModule(ModuleInterface $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getModule(): ModuleInterface
    {
        return $this->module;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
