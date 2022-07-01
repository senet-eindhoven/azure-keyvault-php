<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Model;

use DateTimeInterface;

class SecretList
{
    use UsableTrait;

    public function __construct(
        private string $id,
        private bool $enabled,
        private DateTimeInterface $created,
        private DateTimeInterface $updated,
        private ?DateTimeInterface $nbf = null,
        private ?DateTimeInterface $exp = null,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreated(): DateTimeInterface
    {
        return $this->created;
    }

    public function getUpdated(): DateTimeInterface
    {
        return $this->updated;
    }

    public function getNbf(): ?DateTimeInterface
    {
        return $this->nbf;
    }

    public function getExp(): ?DateTimeInterface
    {
        return $this->exp;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
