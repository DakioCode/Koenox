<?php

namespace Koenox\Custom\pokeball;

use customiesdevs\customies\item\CreativeInventoryInfo as  CII;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ProjectileItem;

abstract class Pokeball extends ProjectileItem
{
    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Unknown", string $texture = "pokeball")
    {
        parent::__construct($identifier, $name);
        
        $creativeInfo = new CII(CII::CATEGORY_ITEMS);
        $this->initComponent($texture, $creativeInfo);
    }

    abstract public function getFilled(): string;
}