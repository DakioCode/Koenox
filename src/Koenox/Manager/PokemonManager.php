<?php

namespace Koenox\Manager;

use Koenox\Custom\pokemon\Pokemon;
use Koenox\Data\PokemonData;
use Koenox\Koenox;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;

class PokemonManager
{
    use SingletonTrait;

    /** @var Config $db */
    private Config $db;

    public function __construct()
    {
        $this->db = Koenox::getInstance()->getConfigFile("db/pokemons.json", Config::JSON);
    }

    public function toData(array $data): PokemonData
    {
        return new PokemonData(
            $data["identifier"],
            $data["health"],
            $data["id"],
            $data["shiny"],
            $data["hasDressor"],
            $data["dressor"],
            $data["out"],
            $data["attacks"]
        );
    }

    public function getAll(): array
    {
        return $this->db->get("pokemons", []);
    }

    protected function set(array $data): void
    {
        $all = $this->getAll();
        $all[$data["id"]] = $data;
        $this->db->set("pokemons", $all);
        $this->db->save();
    }

    public function getData(int $id): PokemonData
    {
        return $this->toData($this->getAll()[(string) $id]);
    }

    public function getLastID(): int
    {
        return Koenox::getInstance()->getConfigFile("config.yml")->get("last_pokemon_id", 1);
    }

    public function updateLastID(): int
    {
        $config = Koenox::getInstance()->getConfigFile("config.yml");
        $config->set("last_pokemon_id", $this->getLastID() + 1);
        $config->save();
        return $this->getLastID();
    }

    public function register(PokemonData $pokemon): void
    {
        Server::getInstance()->getLogger()->alert("Registering with id " . $pokemon->id);

        $data = [
            "identifier" => $pokemon->identifier,
            "hasDressor" => $pokemon->hasDressor,
            "dressor" => $pokemon->dressor,
            "shiny" => $pokemon->shiny,
            "health" => $pokemon->health,
            "id" => $pokemon->id,
            "out" => $pokemon->out,
            "attacks" => []
        ];

        $this->set($data);
    }

    public function isSpawned(int $id, World $world): bool
    {
        foreach ($world->getEntities() as $entity) {
            if ($entity instanceof Pokemon) {
                if ($entity->getPokemonID() === $id)
                    return true;
            }
        } return false;
    }

    public function getSpawned(int $id, World $world): ?Pokemon
    {
        foreach ($world->getEntities() as $entity) {
            if ($entity instanceof Pokemon) {
                if ($entity->getPokemonID() === $id)
                    return $entity;
            }
        } return null;
    }

    public function isRegistered(int $id): bool
    {
        return isset($this->getAll()[(string) $id]);
    }
}
