<?php

namespace Koenox\Command;

use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

abstract class KoenoxCommand extends Command
{
    /** @var bool $gameOnly */
    protected bool $gameOnly = false;

    /** @var bool $staff */
    protected bool $staff = false;

    public function __construct(string $name, string $description, string $usage, array $aliases = [], bool $gameOnly = false, bool $staff = false)
    {
        parent::__construct($name, $description, $usage, $aliases);
        $this->setPermissionMessage("§cVous n'avez pas les permissions requises...");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($this->gameOnly && !$sender instanceof KoenoxPlayer) {
            $sender->sendMessage("§cCette fonctionnalité est uniquement disponnible en jeu...");
            return;
        }

        // TODO: Implement STAFF condition

        $this->onRun($sender, $commandLabel, $args);
    }

    abstract function onRun(CommandSender $sender, string $commandLabel, array $args);

    protected function sendNoArgsMessage(CommandSender $sender): void
    {
        $sender->sendMessage("§cVous avez oublié des §4arguments§c...");
    }

    protected function sendInvArgsMessage(CommandSender $sender): void
    {
        $sender->sendMessage("§cUn ou des argument(s) sont invalide(s)...");
    }
}