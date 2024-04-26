<?php

namespace Koenox\View\fight;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;

class FightMenu extends SimpleForm
{
    public function __construct()
    {
        parent::__construct(function(Player $player, ?int $data = null) {
            if (is_null($data))
                return;
            switch ($data) {
                case 0:
                    $player->sendForm(new FightRequests($player->getXuid()));
                    break;
                
                case 1:
                    $player->sendForm(new FightRequest());
                    break;
            }
        });

        $this->setTitle("Menu de combat");
        $this->setContent("Choisissez l'option que vous souhaitez :");
        $this->addButton("§tMes demandes de combat");
        $this->addButton("§tNouvelle demande de combat");
        $this->addButton("§cAnnuler");
    }
}