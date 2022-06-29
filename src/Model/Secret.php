<?php
declare(strict_types=1);

namespace Senet\AzureKeyVault\Model;

use DateTimeInterface;

final class Secret
{
    public function __construct(
        private string $id,
        private string $value,
        private bool $enabled,
        private DateTimeInterface $created,
        private DateTimeInterface $updated,
        private ?DateTimeInterface $nbf = null,
        private ?DateTimeInterface $exp = null,
        private ?string $contentType = null,
        private ?array $tags = [],
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getTags(): array
    {
        return $this->tags;
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
}
