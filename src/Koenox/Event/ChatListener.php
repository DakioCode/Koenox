<?php

namespace Koenox\Event;

use Koenox\Manager\FightManager;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;

class ChatListener implements Listener
{
    public function onCommand(CommandEvent $e): void
    {
        $sender = $e->getSender();

        if ($sender instanceof KoenoxPlayer) {
            if (FightManager::getInstance()->isInFight($sender->getXuid())) {
                $sender->sendMessage("§cExécution de §4commandes §cimpossible en combat...");
                if (!$e->isCancelled())
                    $e->cancel();
                return;
            }
        }
    }
}