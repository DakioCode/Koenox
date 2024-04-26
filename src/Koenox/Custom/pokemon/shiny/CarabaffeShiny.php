<?php

namespace Koenox\Custom\pokemon\shiny;

use Koenox\Custom\pokemon\base\BaseCarabaffe;
use Koenox\Custom\pokemon\base\BaseCarapuce;
use Koenox\Data\PlayerData;
use Koenox\Data\PokemonData;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;

class CarabaffeShiny extends BaseCarabaffe
{
    public function __construct(Location $location, ?CompoundTag $nbt = null, bool $hasDressor = false, string $dressor = "", int $id = -1, int $health = 44, bool $out = true, array $attacks = [])
    {
        $data = new PokemonData("carabaffe_shiny", $health, $id, true, $hasDressor, $dressor, $out, $attacks);

        parent::__construct($location, $nbt, $data);
    }

    public function getClass(): string
    {
        return self::class;
    }

    public function getName(): string
    {
        return "Carabaffe Shiny";
    }

    public static function getNetworkTypeId(): string
    {
        return "koenox:carabaffe_shiny";
    }
}