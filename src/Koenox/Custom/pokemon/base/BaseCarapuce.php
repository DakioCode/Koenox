<?php

namespace Koenox\Custom\pokemon\base;

use Koenox\Custom\pokemon\Pokemon;
use Koenox\Data\PlayerData;
use Koenox\Data\PokemonData;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;

abstract class BaseCarapuce extends Pokemon
{
    public function __construct(Location $location, ?CompoundTag $nbt = null, ?PokemonData $data = null)
    {
        parent::__construct($location, $nbt, $data);
    }

    public function getChance(): int
    {
        return 195;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(1, 1);
    }

    public static function getMaxHealthPoints(): int
    {
        return 44;
    }
}