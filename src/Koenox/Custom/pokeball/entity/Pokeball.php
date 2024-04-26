<?php

namespace Koenox\Custom\pokeball\entity;

use Koenox\Custom\entity\Pokemon;
use Koenox\Utils\KoenoxPlayer;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Throwable;
use pocketmine\math\RayTraceResult;
use pocketmine\Server;
use pocketmine\world\Position;

abstract class Pokeballa extends Throwable
{
    abstract public function getChance(): int;
    
    protected function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void
    {
        if (!$entityHit instanceof Pokemon) 
            return;

        $owning = $this->getOwningEntity();

        if (!$owning instanceof KoenoxPlayer)
            return;

        if ($entityHit->hasDressor()) {
            $owning->sendActionBarMessage("§cCe Pokémon a déjà un dresseur...");
            return;
        }

        $ballChance = $this->getChance();
        $pokemonChance = $entityHit->getChance();
        $chance = $ballChance + $pokemonChance;

        $number = random_int(1, 400);

        if ($number > $chance) {
            $owning->sendActionBarMessage("§cOups... Le pokémon est sorti de sa Pokéball...");
            return;
        }

        $entityHit->setInvisible();
        $entityHit->teleport(new Position(1000, 100, 1000, $this->getWorld()));
        $entityHit->kill();
        $entityHit->catch($owning, $entityHit::class, $this);

        $owning->sendToastNotification("§2Nouveau Pokémon", "§aVous avez attrapé le Pokémon §2" . $entityHit->getName() . " §a!");
        Server::getInstance()->broadcastMessage("§f[§6Pokémon§f] Le joueur §6" . $owning->getName() . " §fa attrapé §6" . $entityHit->getName() . " §f!");
    
        $entityHit->__destruct();
    }
}