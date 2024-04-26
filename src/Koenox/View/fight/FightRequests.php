<?php

namespace Koenox\View\fight;

use jojoe77777\FormAPI\SimpleForm;
use Koenox\Koenox;
use Koenox\Manager\FightManager;
use pocketmine\player\Player;

class FightRequests extends SimpleForm
{
    public function __construct(string $xuid)
    {
        $requests = [];
        foreach (FightManager::getInstance()->getRequests() as $player => $target) {
            if ($target === $xuid) {
                $requests[] = [$player, $target];
            }
        }

        parent::__construct(function(Player $player, mixed $data = null) {
            if (is_null($data))
                return;

            if (str_starts_with($data, "request_")) {
                $targetXuid = explode("request_", $data)[1];
                $target = Koenox::getInstance()->getPlayerByXuid($targetXuid);
                
                if (!is_null($target))
                    $player->sendForm(new FightRequestManagement($target));
            }
        });

        $this->setTitle("Mes demandes de combat");
        $this->setContent("Cliquez sur les demandes de combat pour les accepter ou les refuser :");
        foreach ($requests as $request) {
            $name = Koenox::getInstance()->getPlayerByXuid($request[0])->getName();
            $this->addButton("Demande de §t" . $name, -1, "", "request_" . $request[0]);
        }
        $this->addButton("§cFermer", -1, "", "close");
    }
}