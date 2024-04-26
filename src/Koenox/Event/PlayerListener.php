<?php

namespace Koenox\Event;

use customiesdevs\customies\item\CustomiesItemFactory;
use Koenox\Custom\pokemon\Pokemon;
use Koenox\Data\PlayerData;
use Koenox\Manager\FightManager;
use Koenox\Manager\PlayerManager;
use Koenox\Manager\PokemonManager;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerListener implements Listener
{
    public function onPlayerJoin(PlayerJoinEvent $e): void
    {
        $player = $e->getPlayer();
        $name = $player->getName();

        if (!$player->hasPlayedBefore()) {
            $e->setJoinMessage("§a+ §fLe joueur §a$name §fvient de rejoindre le serveur pour la §apremière fois §f!");
            $player->getInventory()->addItem(CustomiesItemFactory::getInstance()->get('koenox:pokeball')->setCount(20));
        } else {
            $e->setJoinMessage("§a+ §f$name");
        }

        if (!PlayerManager::getInstance()->isRegistered($player->getXuid()))
            PlayerManager::getInstance()->register(new PlayerData($player->getName(), $player->getXuid(), [], 0));
    }

    public function onPlayerQuit(PlayerQuitEvent $e): void
    {
        FightManager::getInstance()->stop(FightManager::getInstance()->getFightId($e->getPlayer()->getXuid()));
        $e->setQuitMessage("§4- §f" . $e->getPlayer()->getName());
    }

    public function onPlayerCreation(PlayerCreationEvent $e): void
    {
        $e->setPlayerClass(KoenoxPlayer::class);
    }
}
