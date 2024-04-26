<?php

namespace Koenox\View\fight;

use jojoe77777\FormAPI\CustomForm;
use Koenox\Koenox;
use Koenox\Manager\FightManager;
use Koenox\Manager\PlayerManager;
use pocketmine\player\Player;
use pocketmine\Server;

class FightRequest extends CustomForm
{
    public function __construct()
    {
        $players = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $p) {
            $players[] = $p->getName();
        }
        
        parent::__construct(function (Player $player, ?array $data = null) use($players) {
            if (is_null($data)) {
                $player->sendMessage("§cLa demande de combat a été §4annulée");
                return;
            }

            $target = Koenox::getInstance()->getPlayerByName($players[$data["target"]]);
            $message = $data["message"] ?? "";

            if (is_null($target)) {
                $player->sendMessage("§cVotre adversaire s'est §4déconnecté(e)§c... Demande de combat §4annulée");
                return;
            }

            if (!PlayerManager::getInstance()->hasFavoritePokemon($player->getXuid()) || !PlayerManager::getInstance()->hasFavoritePokemon($target->getXuid())) {
                $player->sendMessage("§cVous ou votre adversaire n'avez pas de §4Pokémon préféré§c... Définissez-le pour combattre grâce à la commande §4/favorite");
                return;
            }

            if (!FightManager::getInstance()->request($player, $target)) {
                $player->sendMessage("§cEnvoi de la demande de combat impossible : le joueur est §4en combat §cou vous l'avez §4déjà demandé en combat§c...");
                return;
            }

            $target->sendMessage("[§tCombat§f] Le joueur §t" . $player->getName() . " §fsouhaite vous combattre ! Tapez la commande §t/combat §fpour accepter ou refuser cette demande !");
            $player->sendMessage("§aEnvoie de la demande de combat à §2" . $target->getName());
        });


        $this->setTitle("Nouveau Combat");
        $this->addLabel("Remplissez les champs pour lancer un combat :", 'subtitle');
        $this->addDropdown("Adversaire", $players, null, 'target');
        $this->addInput("Message de provocation", "Ex : J'ai envie de te combattre !", null, 'message');
    }
}
