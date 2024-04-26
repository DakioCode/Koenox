<?php

namespace Koenox\Custom\pokeball;

use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use Koenox\Manager\PokemonManager;
use Koenox\Utils\Pokemons;
use pocketmine\entity\Location;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\item\ItemUseResult;
use pocketmine\Server;
use pocketmine\world\Position;

class PokeballFilled extends Item implements ItemComponents
{
    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Unknown", string $texture = "pokeball")
    {
        parent::__construct($identifier, $name);
        $this->initComponent($texture);
    }

    public function onClickAir(Player $player, Vector3 $directionVector, array &$returnedItems): ItemUseResult
    {
        if (!$this->isValid()) {
            $player->sendMessage("§cCette pokéball est §4invalide§c, merci d'aller voir un §Administrateur §cdu serveur.");
            return ItemUseResult::FAIL();
        }

        $id = $this->getNamedTag()->getTag("PokemonID")->getValue();
        if (!PokemonManager::getInstance()->isRegistered($id)) {
            $player->sendMessage("§cCette pokéball est §4invalide§c, merci d'aller voir un §Administrateur §cdu serveur.");
            return ItemUseResult::FAIL();
        }

        $data = PokemonManager::getInstance()->toData(PokemonManager::getInstance()->getAll()[(string) $id]);

        if ($data->dressor !== $player->getXuid()) {
            $player->sendMessage("§cCe Pokémon ne vous appartient pas...");
            return ItemUseResult::FAIL();
        }

        $pos = $player->getPosition();
        $location = new Location($pos->getX(), $pos->getY(), $pos->getZ(), $pos->getWorld(), 0, 0);

        if ($data->out) {
            $data->out = false;

            if (PokemonManager::getInstance()->isSpawned($data->id, $player->getWorld())) {
                $pokemon = PokemonManager::getInstance()->getSpawned($data->id, $player->getWorld());
            } else {
                $pokemon = Pokemons::getPokemon($data->identifier, $location, null, $data->hasDressor, $data->dressor, $data->id, $data->health, $data->out);
                $pokemon->spawnTo($player);
            }

            $pokemon->setInvisible();
            $pokemon->teleport(new Position(1000, 100, 1000, $player->getWorld()));
            $pokemon->kill();
            $pokemon->__destruct();

            $player->sendActionBarMessage("§aVous avez fait rentrer §2" . $pokemon->getName() . " §adans sa pokéball");
        } else {
            $data->out = true;
            $pokemon = Pokemons::getPokemon($data->identifier, $location, null, $data->hasDressor, $data->dressor, $data->id, $data->health, $data->out);

            $pokemon->spawnTo($player);
            $player->sendActionBarMessage("§aVous avez fait sortir §2" . $pokemon->getName() . " §ade sa pokéball");
        }

        PokemonManager::getInstance()->register($data);
        return ItemUseResult::SUCCESS();
    }

    public function isValid(): bool
    {
        return $this->getNamedTag()->count() === 1;
    }

    public function getMaxStackSize(): int
    {
        return 1;
    }
}
