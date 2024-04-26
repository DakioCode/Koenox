<?php

namespace Koenox\View;

use customiesdevs\customies\item\CustomiesItemFactory;
use Koenox\API\InventoryUI\CustomInventory;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class CenterUI extends CustomInventory
{
    public function __construct()
    {
        parent::__construct(9, "Centre Pokémon");
        $this->setDefaultItems([
            0 => VanillaBlocks::CONCRETE()->setColor(DyeColor::RED())->asItem()->setCustomName("§r§l§4ANNULER"),
            2 => VanillaBlocks::CONCRETE()->setColor(DyeColor::RED())->asItem()->setCustomName("§r§l§4ANNULER"),
            4 => VanillaBlocks::IRON_BARS()->asItem()->setCustomName(" "),
            6 => VanillaBlocks::CONCRETE()->setColor(DyeColor::GREEN())->asItem()->setCustomName("§r§l§aCONFIRMER"),
            8 => VanillaBlocks::CONCRETE()->setColor(DyeColor::GREEN())->asItem()->setCustomName("§r§l§aCONFIRMER"),
        ]);
    }

    public function click(Player $player, int $slot, Item $sourceItem, Item $targetItem): bool
    {
        if ($this->isDefaultItem($sourceItem) || $this->isDefaultItem($targetItem)) {
            return true;
        } elseif ($targetItem->getVanillaName() === CustomiesItemFactory::getInstance()->get("koenox:"))
        return false;
    }
}