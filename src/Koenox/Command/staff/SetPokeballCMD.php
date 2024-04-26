<?php

namespace Koenox\Command\staff;

use Koenox\Command\KoenoxCommand;
use Koenox\Custom\pokeball\PokeballFilled;
use Koenox\Manager\PokemonManager;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\command\CommandSender;

class SetPokeballCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("setpokéball", "Définir l'ID de la pokéball en mains", "/setpokéball", ["définir_pokéball"], true, true);
        $this->setPermission("koenox.command.set_pokeball");
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;

        $itemInHand = $sender->getInventory()->getItemInHand();
        if (!$itemInHand instanceof PokeballFilled) {
            $sender->sendMessage("§cVeuillez tenir une Pokéball en main...");
            return;
        }

        if (!is_null($itemInHand->getNamedTag()->getTag("PokemonID"))) {
            $sender->sendMessage("§cCette pokéball a déjà un ID...");
            return;
        }

        if (!count($args) >= 1) {
            $sender->sendMessage("§cIl vous manque §41 §cargument...");
            return;
        }

        $id = (int) $args[0];
        if (!$id <= 0 || !PokemonManager::getInstance()->isRegistered($id)) {
            $sender->sendMessage("§cVeuilez saisir un ID §4valide§c...");
            return;
        }

        $itemInHand->getNamedTag()->setInt("PokemonID", $id);
        $sender->sendMessage("§aL'identifiant du Pokémon pour cette pokéball a été défini sur §2" . (string) $id . " §a!");
    }
}