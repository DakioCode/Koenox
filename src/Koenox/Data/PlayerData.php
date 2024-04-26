<?php

namespace Koenox\Data;

use Koenox\Utils\player\KoenoxPlayer;

class PlayerData
{
    /** @var string $name */
    public string $name;

    /** @var string $xuid */
    public string $xuid;

    /** @var string[] $pokemons */
    public array $pokemons = [];

    /** @var int $favoritePokemon */
    public int $favoritePokemon;

    public function __construct(string $name, string $xuid, array $pokemons, int $favoritePokemon)
    {
        $this->name = $name;
        $this->xuid = $xuid;
        $this->pokemons = $pokemons;
        $this->favoritePokemon = $favoritePokemon;
    }

    public static function from(KoenoxPlayer $player): self
    {
        return new self($player->getName(), $player->getXuid(), [], 0);
    }
}