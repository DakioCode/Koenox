<?php

namespace Koenox\Custom\pokeball;

use Koenox\Custom\pokemon\Pokemon;
use Koenox\Manager\PlayerManager;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Throwable;
use pocketmine\math\RayTraceResult;

abstract class PokeballEntity extends Throwable
{
    abstract public function getChance(): int;
    
    protected function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void
    {
        $owning = $this->getOwningEntity();
        if (!$entityHit instanceof Pokemon || !$owning instanceof KoenoxPlayer) 
            return;

        if ($entityHit->hasDressor()) {
            $owning->sendActionBarMessage("§cCe Pokémon a déjà un dresseur...");
            return;
        }

        $entityHit->catch(PlayerManager::getInstance()->getData($owning->getXuid()), $this, $owning);
    }
}