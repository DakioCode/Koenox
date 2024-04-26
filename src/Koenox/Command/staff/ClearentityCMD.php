<?php

namespace Koenox\Command\staff;

use Koenox\Command\KoenoxCommand;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\command\CommandSender;

class ClearentityCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("clearentity", "Supprimer toutes les entités présentes dans le monde actuel", "/clearentity", [], true, true);
        $this->setPermission("koenox.command.clearentity");
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;

        foreach ($sender->getWorld()->getEntities() as $entity) {
            $entity->kill();
            if (!$entity instanceof KoenoxPlayer)
                $entity->__destruct();
        }

        $sender->sendMessage("§aLes entités du monde §2" . $sender->getWorld()->getDisplayName() . " §aon été §2suprimées §a!");
    }
}