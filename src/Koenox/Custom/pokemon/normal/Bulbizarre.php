<?php

namespace Koenox\Custom\pokemon\normal;

use Koenox\Custom\pokemon\base\BaseBulbizarre;
use Koenox\Custom\pokemon\base\BaseHerbizarre;
use Koenox\Data\PlayerData;
use Koenox\Data\PokemonData;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;

class Bulbizarre extends BaseBulbizarre
{
    public function __construct(Location $location, ?CompoundTag $nbt = null, bool $hasDressor = false, string $dressor = "", int $id = -1, int $health = 44, bool $out = true, $attacks = [])
    {
        $data = new PokemonData("bulbizarre", $health, $id, false, $hasDressor, $dressor, $out, $attacks);

        parent::__construct($location, $nbt, $data);
    }

    public function getClass(): string
    {
        return self::class;
    }

    public function getName(): string
    {
        return "Bulbizarre";
    }

    public static function getNetworkTypeId(): string
    {
        return "koenox:bulbizarre";
    }
}
