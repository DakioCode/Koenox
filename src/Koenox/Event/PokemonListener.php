<?php

namespace Koenox\Event;

use Koenox\Custom\pokemon\Pokemon;
use Koenox\Utils\player\KoenoxPlayer;
use Koenox\View\pokemon\PokemonInfo;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

class PokemonListener implements Listener
{
    public function onEntityDamageByEntity(EntityDamageByEntityEvent $e): void
    {
        $damager = $e->getDamager();
        $victim = $e->getEntity();

        if ($victim instanceof Pokemon) {
            if (!$e->isCancelled())
                $e->cancel();

            if ($damager instanceof KoenoxPlayer) {
                $damager->sendForm(new PokemonInfo($victim->getData()));
            }
        }
    }

    public function onEntityDamage(EntityDamageEvent $e): void
    {
        $entity = $e->getEntity();
        if ($entity instanceof Pokemon) {
            if (!$e->isCancelled())
                $e->cancel();
        }
    }
}