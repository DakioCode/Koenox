<?php

namespace Koenox\Command\staff;

use Koenox\Command\KoenoxCommand;
use Koenox\Koenox;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

class SetArenaCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("set_arena", "Définir la position de l'arène", "/set_arena", ["set_arene"], true, true);
        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;

        $pos = $sender->getPosition();
        $position = [
            $pos->getX(),
            $pos->getY(),
            $pos->getZ(),
            $pos->getWorld()->getDisplayName()
        ];

        Koenox::getInstance()->getDefaultConfig()->set("arena", $position);
        Koenox::getInstance()->getDefaultConfig()->save();
        $sender->sendMessage("§aVous avez modifié la position de l'arène");
    }
}