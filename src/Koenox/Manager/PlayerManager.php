<?php

namespace Koenox\Manager;

use Koenox\Data\PlayerData;
use Koenox\Koenox;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class PlayerManager
{
    use SingletonTrait;

    /** @var Config $db */
    private Config $db;

    public function __construct()
    {
        $this->db = Koenox::getInstance()->getConfigFile("db/players.json", Config::JSON);
    }

    private function set(string $k, mixed $v = true): void
    {
        $this->db->set($k, $v);
        $this->db->save();
    }

    public function getAll(): array
    {
        return $this->db->get("players", []);
    }

    public function register(PlayerData $player)
    {
        $data = [
            "name" => $player->name,
            "xuid" => $player->xuid,
            "pokemons" => $player->pokemons,
            "favorite" => $player->favoritePokemon
        ];

        $all = $this->getAll();
        $all[(string) $player->xuid] = $data;
        $this->set("players", $all);
    }

    public function isRegistered(string $xuid): bool
    {
        return isset($this->getAll()[$xuid]);
    }

    public function getData(string $xuid): PlayerData
    {
        $player = $this->getAll()[$xuid];
        return new PlayerData(
            $player["name"],
            $player["xuid"],
            $player["pokemons"],
            $player["favorite"]
        );
    }

    public function setFavoritePokemon(string $xuid, int $id): void
    {
        $all = $this->getAll();
        $all[$xuid]["favorite"] = (string) $id;
        $this->set("players", $all);
    }

    public function hasFavoritePokemon(string $xuid): bool
    {
        Server::getInstance()->getLogger()->notice((string) $this->getAll()[$xuid]["favorite"]);
        return $this->getAll()[$xuid]["favorite"] > 0;
    }
}