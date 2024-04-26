<?php

namespace Koenox\Custom\pokemon\normal;

use Koenox\Custom\pokemon\base\BaseCarabaffe;
use Koenox\Custom\pokemon\base\BaseTortank;
use Koenox\Data\PlayerData;
use Koenox\Data\PokemonData;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;

class Tortank extends BaseTortank
{
    public function __construct(Location $location, ?CompoundTag $nbt = null, bool $hasDressor = false, string $dressor = "", int $id = -1, int $health = 44, bool $out = true, array $attacks = [])
    {
        $data = new PokemonData("tortank", $health, $id, false, $hasDressor, $dressor, $out, $attacks);

        parent::__construct($location, $nbt, $data);
    }

    public function getClass(): string
    {
        return self::class;
    }

    public function getName(): string
    {
        return "Tortank";
    }

    public static function getNetworkTypeId(): string
    {
        return "koenox:tortank";
    }
}