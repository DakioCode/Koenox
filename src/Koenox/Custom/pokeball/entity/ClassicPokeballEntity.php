<?php

namespace Koenox\Custom\pokeball\entity;

use Koenox\Custom\pokeball\PokeballEntity;

class ClassicPokeballEntity extends PokeballEntity
{
    public static function getNetworkTypeId(): string
    {
        return 'koenix:pokeball_entity';
    }

    public function getChance(): int
    {
        return 100;
    }
}