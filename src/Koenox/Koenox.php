<?php

/**
 * ██╗  ██╗ ██████╗ ███████╗███╗   ██╗██╗██╗  ██╗████████╗███████╗ █████╗ ███╗   ███╗
 * ██║ ██╔╝██╔═══██╗██╔════╝████╗  ██║██║╚██╗██╔╝╚══██╔══╝██╔════╝██╔══██╗████╗ ████║
 * █████╔╝ ██║   ██║█████╗  ██╔██╗ ██║██║ ╚███╔╝    ██║   █████╗  ███████║██╔████╔██║
 * ██╔═██╗ ██║   ██║██╔══╝  ██║╚██╗██║██║ ██╔██╗    ██║   ██╔══╝  ██╔══██║██║╚██╔╝██║
 * ██║  ██╗╚██████╔╝███████╗██║ ╚████║██║██╔╝ ██╗   ██║   ███████╗██║  ██║██║ ╚═╝ ██║
 * ╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝   ╚═╝   ╚══════╝╚═╝  ╚═╝╚═╝     ╚═╝                                                                                                                                
 */

namespace Koenox;

use customiesdevs\customies\entity\CustomiesEntityFactory;
use customiesdevs\customies\item\CustomiesItemFactory;
use Koenox\API\InventoryUI\InventoryUI;
use Koenox\Command\all\CentreCMD;
use Koenox\Command\all\CombatCMD;
use Koenox\Command\all\FavoriteCMD;
use Koenox\Command\all\XuidCMD;
use Koenox\Command\staff\ClearentityCMD;
use Koenox\Command\staff\InventoryCMD;
use Koenox\Command\staff\PokemonCMD;
use Koenox\Command\staff\SetArenaCMD;
use Koenox\Command\staff\SetPokeballCMD;
use Koenox\Custom\pokeball\entity\ClassicPokeballEntity;
use Koenox\Custom\pokeball\item\ClassicPokeball;
use Koenox\Custom\pokeball\PokeballFilled;
use Koenox\Custom\pokemon\normal\Bulbizarre;
use Koenox\Custom\pokemon\normal\Carabaffe;
use Koenox\Custom\pokemon\normal\Carapuce;
use Koenox\Custom\pokemon\normal\Dracofeu;
use Koenox\Custom\pokemon\normal\Herbizarre;
use Koenox\Custom\pokemon\normal\Reptincel;
use Koenox\Custom\pokemon\normal\Tortank;
use Koenox\Custom\pokemon\shiny\BulbizarreShiny;
use Koenox\Custom\pokemon\shiny\CarabaffeShiny;
use Koenox\Custom\pokemon\shiny\CarapuceShiny;
use Koenox\Custom\pokemon\shiny\DracofeuShiny;
use Koenox\Custom\pokemon\shiny\HerbizarreShiny;
use Koenox\Custom\pokemon\shiny\ReptincelShiny;
use Koenox\Custom\pokemon\shiny\TortankShiny;
use Koenox\Event\ChatListener;
use Koenox\Event\PlayerListener;
use Koenox\Event\PokemonListener;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;

class Koenox extends PluginBase
{
    use SingletonTrait;

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        InventoryUI::setup($this);
        $this->init();
        $this->getLogger()->info("Plugin Enabled !");
    }

    protected function onDisable(): void
    {
        $this->getLogger()->info("Plugin Disabled !");
    }

    private function init(): void
    {
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder() . "db");

        $this->saveResource('config.yml');
        $this->saveResource("db/pokemons.json");
        $this->saveResource("db/players.json");
        $this->saveResource("db/fights.json");

        // Events
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PokemonListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ChatListener(), $this);

        // Commands
        $this->getServer()->getCommandMap()->registerAll("", [
            new XuidCMD(),
            new PokemonCMD(),
            new ClearentityCMD(),
            new InventoryCMD(),
            new CentreCMD(),
            new CentreCMD(),
            new CombatCMD(),
            new SetArenaCMD(),
            new FavoriteCMD(),
            new SetPokeballCMD(),
        ]);

        // Pokeballs
        CustomiesItemFactory::getInstance()->registerItem(ClassicPokeball::class, "koenox:pokeball", "Pokéball");
        CustomiesItemFactory::getInstance()->registerItem(PokeballFilled::class, "koenox:pokeball_filled", "Pokéball remplie");

        // Pokeball Entities
        // CustomiesEntityFactory::getInstance()->registerEntity(ClassicPokeballEntity::class, "koenox:pokeball_entity");
        $this->registerEntity(ClassicPokeballEntity::class, 'koenox:pokeball_entity');

        // Not shiny Pokémons
        CustomiesEntityFactory::getInstance()->registerEntity(Carapuce::class, Carapuce::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(Carabaffe::class, Carabaffe::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(Tortank::class, Tortank::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(Bulbizarre::class, Bulbizarre::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(Herbizarre::class, Herbizarre::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(Reptincel::class, Reptincel::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(Dracofeu::class, Dracofeu::getNetworkTypeId());

        // Shiny Pokémons
        CustomiesEntityFactory::getInstance()->registerEntity(CarapuceShiny::class, CarapuceShiny::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(CarabaffeShiny::class, CarabaffeShiny::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(TortankShiny::class, TortankShiny::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(BulbizarreShiny::class, BulbizarreShiny::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(HerbizarreShiny::class, HerbizarreShiny::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(ReptincelShiny::class, ReptincelShiny::getNetworkTypeId());
        CustomiesEntityFactory::getInstance()->registerEntity(DracofeuShiny::class, DracofeuShiny::getNetworkTypeId());
    }

    public function getPlayerByName(string $name): ?Player
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player->getName() === $name) return $player;
        }
        return null;
    }

    public function getPlayerByXuid(string $xuid): ?Player
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player->getXuid() === $xuid) return $player;
        }
        return null;
    }

    public function getConfigFile(string $name, int $type = Config::DETECT): Config
    {
        return new Config($this->getDataFolder() . $name, $type);
    }

    public function getDefaultConfig(): Config
    {
        return $this->getConfigFile("config.yml", Config::YAML);
    }

    private function registerEntity(string $class, string $identifier): void
    {
        CustomiesEntityFactory::getInstance()->registerEntity($class, $identifier, static function (World $world, CompoundTag $nbt) use ($class): Entity {
            return new $class(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        });
    }

    public function arrayToString(array $value): string
    {
        $result = "";
        foreach ($value as $line) {
            $result .= "\n" . (string) $line;
        }
        return $result;
    }
}
