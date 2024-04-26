<?php

namespace Koenox\Command\all;

use Koenox\Command\KoenoxCommand;
use Koenox\Utils\player\KoenoxPlayer;
use Koenox\View\fight\FightMenu;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

class CombatCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("combat", "Gérer ses combats", "/combat", ["fight"], true);
        $this->setPermission(DefaultPermissions::ROOT_USER);
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;

        $sender->sendForm(new FightMenu());
    }
}