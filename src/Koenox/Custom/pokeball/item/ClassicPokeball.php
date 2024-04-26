<?php

namespace Koenox\Custom\pokeball\item;

use customiesdevs\customies\item\ItemComponents;
use Koenox\Custom\pokeball\entity\ClassicPokeballEntity;
use Koenox\Custom\pokeball\Pokeball;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;

class ClassicPokeball extends Pokeball implements ItemComponents
{
    public function __construct(ItemIdentifier $identifier, string $name = "Unknown")
    {
        parent::__construct($identifier, $name, "pokeball");
    }

    public function getFilled(): string
    {
        return ClassicPokeballFilled::class;
    }

    protected function createEntity(Location $location, Player $thrower) : Throwable
    {
        return new ClassicPokeballEntity($location, $thrower);
    }

    public function getThrowForce(): float
    {
        return 1.5;
    }
}