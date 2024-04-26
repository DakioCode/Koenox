<?php

namespace Koenox\Custom\pokeball\item;

use Koenox\Custom\pokeball\PokeballFilled;
use pocketmine\item\ItemIdentifier;

class ClassicPokeballFilled extends PokeballFilled
{
    public function __construct(ItemIdentifier $identifier, string $name = "Unknown")
    {
        parent::__construct($identifier, $name, "pokeball");
    }
}