<?php

namespace Koenox\Command\staff;

use Koenox\Command\KoenoxCommand;
use Koenox\Custom\pokemon\normal\Carapuce;
use Koenox\Utils\player\KoenoxPlayer;
use Koenox\Data\PlayerData;
use Koenox\Data\PokemonData;
use Koenox\Manager\PokemonManager;
use Koenox\Utils\Pokemons;
use pocketmine\command\CommandSender;
use pocketmine\entity\Location;

class PokemonCMD extends KoenoxCommand
{
    public function __construct()
    {
        parent::__construct("pokémon", "Faire apparaître un Pokémon", "/pokémon <name>", [], true, true);
        $this->setPermission("koenox.pokemons.spawn");
    }

    public function onRun(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof KoenoxPlayer)
            return;

        if (count($args) < 1) {
            $sender->sendMessage("§cVeuillez entrer un nom de Pokémon...");
            return;
        }

        if (isset($args[1]) && $args[1] === "shiny")
            $shiny = true;
        else
            $shiny = false;

        $name = Pokemons::getRealName($args[0], $shiny);

        if (is_null(Pokemons::get($name))) {
            $sender->sendMessage("§cLe nom de Pokémon que vous avez entré est §4invalide §c...");
            return;
        }

        $pos = $sender->getPosition();
        $pokemon = Pokemons::getPokemon($name, new Location($pos->getX(), $pos->getY(), $pos->getZ(), $pos->getWorld(), 0, 0), null, false, "", PokemonManager::getInstance()->updateLastID(), 44, true);

        $pokemon->spawnToAll();
        $sender->sendMessage("§aVous avez fait apparaître le Pokémon §2" . $pokemon->getName() . " §a!");
    }
}