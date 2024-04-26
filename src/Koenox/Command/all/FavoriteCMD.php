<?php

namespace Koenox\Command\all;

use Koenox\Command\KoenoxCommand;
use Koenox\Custom\pokeball\PokeballFilled;
use Koenox\Manager\PlayerManager;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

class FavoriteCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("favorite", "Défénir son Pokémon préféré (pour les combats)", "/favorite", ["préféré", "pokémon_préféré"], true);
        $this->setPermission(DefaultPermissions::ROOT_USER);
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;
        
        $itemInHand = $sender->getInventory()->getItemInHand();
        if (!$itemInHand instanceof PokeballFilled) {
            $sender->sendMessage("§cVeuillez tenir la pokéball de votre Pokémon en mains...");
            return;
        }

        $pokemonID = (int) $itemInHand->getNamedTag()->getTag("PokemonID")->getValue();

        PlayerManager::getInstance()->setFavoritePokemon($sender->getXuid(), $pokemonID);
        $sender->sendMessage("§aVous avez défini votre Pokémon favori !");
    }
}