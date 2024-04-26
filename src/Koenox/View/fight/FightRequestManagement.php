<?php

namespace Koenox\View\fight;

use jojoe77777\FormAPI\ModalForm;
use Koenox\Data\FightData;
use Koenox\Manager\FightManager;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\player\Player;

class FightRequestManagement extends ModalForm
{
    public function __construct(KoenoxPlayer $player1)
    {
        parent::__construct(function (Player $player, ?bool $data = null) use($player1) {
            if (is_null($data))
                return;

            switch ($data) {
                case true:
                    if ($player1->isClosed()) {
                        $player->sendMessage("§cVotre adversaire s'est §4déconnecté§c...");
                        break;
                    }

                    $player1->sendMessage("§f[§tCombat§f] Téléportation dans l'§tarène §fet lancement du combat contre §t" . $player->getName() . " §f!");
                    $player->sendMessage("§f[§tCombat§f] Téléportation dans l'§tarène §fet lancement du combat contre §t" . $player1->getName() . " §f!");

                    FightManager::getInstance()->enterArena($player1, 2);
                    FightManager::getInstance()->enterArena($player, 1);

                    FightManager::getInstance()->start(new FightData(
                        $player->getXuid(),
                        $player1->getXuid(),
                        1, 
                        1
                    ));
                    break;

                case false:
                    FightManager::getInstance()->removeRequest($player1->getXuid());

                    if (!$player1->isClosed()) {
                        $player1->sendMessage("§f[§tCombat§f] Le joueur §t" . $player->getName() . " §fa annulé votre demande de combat");
                    }

                    $player->sendMessage("§aVous avez annulé la demande de combat");
                    break;
            }
        });

        $this->setTitle("Gestion de la demande de combat");
        $this->setContent("Cliquez sur un bouton ci-dessous : \n §tJoueur adverse : §f" . $player1->getName());
        $this->setButton1("§aAccepter");
        $this->setButton2("§cRefuser");
    }
}
