<?php

namespace Koenox\Command\staff;

use Koenox\API\InventoryUI\CustomInventory;
use Koenox\Command\KoenoxCommand;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

class InventoryCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("inventory", "Ouvrir un inventaire", "/inventory <slots> <title>", [], true, true);
        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;

        if (count($args) < 2) {
            $sender->sendMessage("§cIl vous manque un ou plusieur(s) argument(s)...");
            return;
        }

        if ($args[0] <= 0) {
            $sender->sendMessage("§cOn ou des argument(s) sont invalide(s)...");
            return;
        }

        $sender->setCurrentWindow(new CustomInventory((int) $args[0], (string) $args[1]));
    }
}