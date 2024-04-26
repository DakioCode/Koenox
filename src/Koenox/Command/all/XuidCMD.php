<?php

namespace Koenox\Command\all;

use Koenox\Command\KoenoxCommand;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

class XuidCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("xuid", "Obtenir son XUID", "/xuid", [], true);
        $this->setPermission(DefaultPermissions::ROOT_USER);
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;

        $sender->sendMessage("§aVotre XUID est : §2" . $sender->getXuid());
    }
}