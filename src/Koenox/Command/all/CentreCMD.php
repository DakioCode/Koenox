<?php

namespace Koenox\Command\all;

use Koenox\Command\KoenoxCommand;
use Koenox\Utils\player\KoenoxPlayer;
use Koenox\View\CenterUI;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

class CentreCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("centre", "Ouvrir l'interface du centre Pokémon", "/centre", ["joelle", "soin"], true);
        $this->setPermission(DefaultPermissions::ROOT_USER);
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;

        $sender->setCurrentWindow(new CenterUI());
    }
}