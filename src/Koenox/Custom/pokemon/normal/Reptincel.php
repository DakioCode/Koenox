<?php

namespace Koenox\Custom\pokemon\normal;

use Koenox\Custom\pokemon\base\BaseBulbizarre;
use Koenox\Custom\pokemon\base\BaseReptincel;
use Koenox\Data\PlayerData;
use Koenox\Data\PokemonData;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;

class Reptincel extends BaseReptincel
{
    public function __construct(Location $location, ?CompoundTag $nbt = null, bool $hasDressor = false, string $dressor = "", int $id = -1, int $health = 44, bool $out = true, array $attacks = [])
    {
        $data = new PokemonData("reptincel", $health, $id, false, $hasDressor, $dressor, $out, $attacks);

        parent::__construct($location, $nbt, $data);
    }

    public function getClass(): string
    {
        return self::class;
    }

    public function getName(): string
    {
        return "Reptincel";
    }

    public static function getNetworkTypeId(): string
    {
        return "koenox:reptincel";
    }
}
