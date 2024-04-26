<?php

namespace Koenox\Utils;

use Koenox\Custom\pokemon\normal\Bulbizarre;
use Koenox\Custom\pokemon\normal\Carabaffe;
use Koenox\Custom\pokemon\normal\Carapuce;
use Koenox\Custom\pokemon\normal\Dracofeu;
use Koenox\Custom\pokemon\normal\Herbizarre;
use Koenox\Custom\pokemon\normal\Reptincel;
use Koenox\Custom\pokemon\normal\Tortank;
use Koenox\Custom\pokemon\Pokemon;
use Koenox\Custom\pokemon\shiny\BulbizarreShiny;
use Koenox\Custom\pokemon\shiny\CarabaffeShiny;
use Koenox\Custom\pokemon\shiny\CarapuceShiny;
use Koenox\Custom\pokemon\shiny\DracofeuShiny;
use Koenox\Custom\pokemon\shiny\HerbizarreShiny;
use Koenox\Custom\pokemon\shiny\ReptincelShiny;
use Koenox\Custom\pokemon\shiny\TortankShiny;
use Koenox\Manager\PokemonManager;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;

class Pokemons
{
    /** @var string[] $pokemons */
    private static array $pokemons = [
        "carapuce" => Carapuce::class,
        "carapuce_shiny" => CarapuceShiny::class,

        "carabaffe" => Carabaffe::class,
        "carabaffe_shiny" => CarabaffeShiny::class,

        "tortank" => Tortank::class,
        "tortank_shiny" => TortankShiny::class,

        "bulbizarre" => Bulbizarre::class,
        "bulbizarre_shiny" => BulbizarreShiny::class,

        "herbizarre" => Herbizarre::class,
        "herbizarre_shiny" => HerbizarreShiny::class,

        "reptincel" => Reptincel::class,
        "reptincel_shiny" => ReptincelShiny::class,

        "dracaufeu" => Dracofeu::class,
        "dracaufeu_shiny" => DracofeuShiny::class,
    ];

    /**
     * Returns all Pokemon classes in the array
     * @return string[]
     */
    public static function getAll(): array
    {
        return self::$pokemons;
    }

    /**
     * Returns the class of one Pokemon
     * @return ?string
     */
    public static function get(string $name): ?string
    {
        return isset(self::getAll()[$name]) ? self::getAll()[$name] : null;
    }

    /**
     * Returns a new instance of a Pokemon
     * @return ?Pokemon
     */
    public static function getPokemon(string $name, Location $location, ?CompoundTag $nbt = null, bool $hasDressor = false, string $dressor = "", int $id = -1, int $health = 44, bool $out = true): ?Pokemon
    {
        $pokemon = self::get($name);
        return is_null($pokemon) ? null : new $pokemon($location, $nbt, $hasDressor, $dressor, $id, $health, $out);
    }

    /**
     * Returns the real name of a Pokemon
     * @return string
     */
    public static function getRealName(string $name, bool $shiny): string
    {
        return $shiny ? $name . "_shiny" : $name;
    }
}