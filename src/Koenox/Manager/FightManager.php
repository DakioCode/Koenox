<?php

namespace Koenox\Manager;

use Koenox\Data\FightData;
use Koenox\Koenox;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;

class FightManager
{
    use SingletonTrait;

    /** @var Config $db */
    private Config $db;

    /** @var string[] $requests */
    private array $requests = [
        "player" => "target"
    ];

    public function __construct()
    {
        $this->db = Koenox::getInstance()->getConfigFile("db/pokemons.json", Config::JSON);
    }

    public function getAll(): array
    {
        return $this->db->get("fights", []);
    }

    protected function set(array $data): void
    {
        $all = $this->getAll();
        $all[$data["id"]] = $data;

        $this->db->set("fights", $all);
        $this->db->save();
    }

    public function getLastId(): int
    {
        return Koenox::getInstance()->getDefaultConfig()->get("last_fight_id");
    }

    public function updateLastId(): int
    {
        Koenox::getInstance()->getDefaultConfig()->set("last_fight_id", $this->getLastId() + 1);
        Koenox::getInstance()->getDefaultConfig()->save();
        return $this->getLastId();
    }

    public function toData(array $fight): FightData
    {
        return new FightData(
            $fight["player1"],
            $fight["player2"],
            $fight["pokemon1"],
            $fight["pokemon2"]
        );
    }

    public function isInFight(string $player): bool
    {
        foreach ($this->getAll() as $fight) {
            $data = $this->toData($fight);
            if ($fight["player1"] === $player || $fight["player2"] === $player)
                return true;
        } return false;
    }

    public function request(KoenoxPlayer $player, KoenoxPlayer $target): bool
    {
        if (isset($this->requests[$player->getXuid()]) || $this->isInFight($player->getXuid()) || $this->isInFight($target->getXuid()))
            return false;

        $this->requests[$player->getXuid()] = $target->getXuid();
        return true;
    }

    public function getRequests(string $xuid = ""): array
    {
        if ($xuid === "")
            return $this->requests;
        $requests = [];
        foreach ($this->requests as $p => $t) {
            if ($p === $xuid || $t === $xuid)
                $requests[] = [$p => $t];
        } return $requests;
    }

    public function removeRequest(string $player): void
    {
        unset($this->requests[$player]);
    }

    public function enterArena(KoenoxPlayer $player, int $arena): void
    {
        $position = Koenox::getInstance()->getDefaultConfig()->get("arena")[(string) $arena];
        $pos = new Position($position[0], $position[1], $position[2], Server::getInstance()->getWorldManager()->getWorldByName($position[3]));

        $player->teleport($pos);
    }

    public function start(FightData $data): void
    {
        $this->set([
            "player1" => $data->player1,
            "player2" => $data->player2,
            "pokemon1" => $data->pokemon1,
            "pokemon2" => $data->pokemon2,
            "id" => $this->updateLastId()
        ]);
    }

    public function getFightId(string $player): int
    {
        foreach ($this->getAll() as $id => $fight) {
            if ($fight["player1"] === $player || $fight["player2"] === $player)
                return (int) $id;
        } return -1;
    }

    public function stop(int $id): void
    {
        $all = $this->getAll();
        if (isset($all[(string) $id]))
            unset($all[(string) $id]);
        $this->db->set("fights", $all);
        $this->db->save();
    }
}