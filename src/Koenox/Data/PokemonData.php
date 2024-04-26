<?php

namespace Koenox\Data;

class PokemonData
{
    public readonly string $identifier;
    public int $health;
    public int $id;
    public readonly bool $shiny;
    public bool $hasDressor;
    public string $dressor;
    public bool $out;

    /** @var AttackData */
    public array $attacks;

    public function __construct(string $identifier, int $health, int $id = -1, bool $shiny = false, bool $hasDressor = false, string $dressor = "", bool $out = true, array $attacks = []) {        
        $this->identifier = $identifier;
        $this->health = $health;
        $this->id = $id;
        $this->shiny = $shiny;
        $this->hasDressor = $hasDressor;
        $this->dressor = $dressor;
        $this->out = $out;
        $this->attacks = $attacks;
    }

    private function edit(array $data): void
    {

    }
}
