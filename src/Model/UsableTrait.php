<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Model;

trait UsableTrait
{
    public function isUsable(): bool
    {
        $expTimestamp = $this->getExp()?->getTimestamp();
        $nbfTimestamp = $this->getNbf()?->getTimestamp();

        if ($this->isEnabled() === false) {
            return false;
        }
        if ($expTimestamp !== null && $expTimestamp <= time()) {
            return false;
        }
        if ($nbfTimestamp !== null && $nbfTimestamp >= time()) {
            return false;
        }

        return true;
    }
}
