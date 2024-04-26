<?php

namespace Koenox\Custom\pokemon;

use customiesdevs\customies\item\CustomiesItemFactory;
use Koenox\Custom\pokeball\PokeballEntity;
use Koenox\Data\PlayerData;
use Koenox\Data\PokemonData;
use Koenox\Manager\PlayerManager;
use Koenox\Manager\PokemonManager;
use Koenox\Utils\player\KoenoxPlayer;
use pocketmine\block\Air;
use pocketmine\block\Liquid;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AnimateEntityPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\Server;
use pocketmine\world\Position;
use Throwable;

abstract class Pokemon extends Living
{
    /** @var PokemonData */
    private PokemonData $baseData;

    /** @var ?CompoundTag $nbt */
    private ?CompoundTag $nbt = null;

    /** @var int $id */
    public int $pokemonID;

    public Vector3 $randomPosition;
    protected float $jumpVelocity = 0.45;
    private int $findNewPosition = 0;
    private float $speed = 0.35;
    private int $jumpTick = 30;
    protected bool $canWalk = true;

    public function __construct(Location $location, ?CompoundTag $nbt = null, ?PokemonData $data = null)
    {
        PokemonManager::getInstance()->register($data);
        $this->baseData = $data;
        $this->pokemonID = $data->id;
        parent::__construct($location, $nbt);
        $this->setNameTag($this->getName());

        $this->setNameTagAlwaysVisible();
    }

    /**
     * Returns the chance you have of catching the Pokémon
     * @return int
     */
    abstract public function getChance(): int;

    /**
     * Returns the Pokémon's classname and the namespace (self::class)
     * @return string
     */
    abstract public function getClass(): string;

    /**
     * Returns the max Pokémon's health
     * @return int
     */
    abstract public static function getMaxHealthPoints(): int;

    /**
     * Returns the Pokémon's custom identifier
     * @return string
     */
    public function getBaseIdentifier(): string
    {
        return $this->getData()->identifier;
    }

    /**
     * Returns the Pokémon's base identifier (without "_shiny" for shinies)
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->getData()->identifier;
    }

    /**
     * Returns if the Pokémon has a dressor
     * @return bool
     */
    public function hasDressor(): bool
    {
        return $this->getData()->hasDressor;
    }

    /**
     * Returns the Pokémon's dressor
     * @return ?PlayerData
     */
    public function getDressor(): string
    {
        return $this->getData()->dressor;
    }

    /**
     * Sets the Pokémon's dressor
     */
    public function setDressor(PlayerData $dressor): void
    {
        $this->getData()->dressor = $dressor;
    }

    /**
     * Returns if the Pokémon is shiny
     * @return bool
     */
    public function isShiny(): bool
    {
        return $this->getData()->shiny;
    }

    /**
     * Returns the Pokémon's custom health
     * @return int
     */
    public function getHealthPoints(): int
    {
        return $this->getData()->health;
    }

    /**
     * Sets the Pokémon's custom health
     */
    public function setHealthPoints(int $amount): void
    {
        $data = $this->getData();
        $data->health = $amount;
        $this->register($data);
    }

    /**
     * The parent's update function
     * In this class, this function allows to the Pokémon to move
     * @see https://github.com/pmmp/PocketMine-MP/blob/11f119551dec2771f0af8b9ae25e88a67d854b16/src/entity/Entity.php#L965
     */
    public function onUpdate(int $currentTick): bool
    {
        if ($this->getLocation()->y <= 1) {
            $this->teleport($this->getWorld()->getSpawnLocation());
        }

        if ($this->findNewPosition === 0 || $this->getLocation()->distance($this->randomPosition) <= 2) {
            $this->findNewPosition = mt_rand(150, 300);
            $this->generateRandomPosition();
        }

        --$this->findNewPosition;
        --$this->jumpTick;

        if ($this->isUnderwater()) {
            $this->motion->y = $this->gravity * 2;
            $this->jumpVelocity = 0.54;
        }

        $position = $this->randomPosition;
        $x = $position->x - $this->getLocation()->getX();
        $z = $position->z - $this->getLocation()->getZ();

        if ($x * $x + $z * $z < 4 + $this->getScale()) {
            $this->motion->x = 0;
            $this->motion->z = 0;
        } else {
            $this->motion->x = $this->speed * 0.15 * ($x / (abs($x) + abs($z)));
            $this->motion->z = $this->speed * 0.15 * ($z / (abs($x) + abs($z)));
        }

        $this->setRotation(1, 5);

        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        $this->updateMovement();

        parent::setHealth($this->getMaxHealth());
        $packet = AnimateEntityPacket::create(
            "idle.carapuce.animation",
            "",
            "",
            0,
             "",
            0, 
            [
                $this->getId()
            ],
        );

        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $player->getNetworkSession()->sendDataPacket($packet);
        }

        return parent::onUpdate($currentTick);
    }

    /**
     * Generates a random position for the Pokémon's movement
     */
    public function generateRandomPosition(): void
    {
        $minX = $this->getLocation()->getFloorX() - 8;
        $maxX = $minX + 16;
        $minY = $this->getLocation()->getFloorY() - 8;
        $maxY = $minY + 16;
        $minZ = $this->getLocation()->getFloorZ() - 8;
        $maxZ = $minZ + 16;
        $world = $this->getWorld();

        $x = mt_rand($minX, $maxX);
        $y = mt_rand($minY, $maxY);
        $z = mt_rand($minZ, $maxZ);

        for ($attempts = 0; $attempts < 16; ++$attempts) {
            while ($y >= 0 and !$world->getBlockAt($x, $y, $z)->isSolid()) {
                $y--;
            }

            $blockAboveEntity = $world->getBlockAt($x, $y + 1, $z);
            $blockBelowEntity = $world->getBlockAt($x + 1, $y - 1, $z);
            if (!$blockAboveEntity instanceof Air || $blockBelowEntity instanceof Liquid) {
                continue;
            }

            break;
        }

        $this->randomPosition = new Vector3($x, $y + 1, $z);
    }

    public function catch(PlayerData $data, PokeballEntity $pokeball, KoenoxPlayer $dressor): void
    {
        $ballChance = $pokeball->getChance();
        $pokemonChance = $this->getChance();
        $chance = $ballChance + $pokemonChance;

        $number = random_int(1, 400);

        if ($number > $chance) {
            $dressor->sendActionBarMessage("§cOups... Le pokémon est sorti de sa Pokéball...");
            return;
        }

        $this->setInvisible();
        $this->teleport(new Position(1000, 100, 1000, $this->getWorld()));
        $this->kill();

        $data->pokemons[] = (string) $this->pokemonID;
        PlayerManager::getInstance()->register($data);

        $pokemonData = $this->getData();
        $pokemonData->dressor = $data->xuid;
        $pokemonData->hasDressor = true;
        $pokemonData->out = false;
        $this->register($pokemonData);

        $filled = CustomiesItemFactory::getInstance()->get("koenox:pokeball_filled");
        $filled->getNamedTag()->setInt("PokemonID", $this->getData()->id);
        $dressor->getInventory()->addItem($filled);

        $dressor->sendToastNotification("§2Nouveau Pokémon", "§aVous avez attrapé le Pokémon §2" . $this->getName() . " §a!");
        Server::getInstance()->broadcastMessage("§f[§6Pokémon§f] Le joueur §6" . $dressor->getName() . " §fa attrapé §6" . $this->getName() . " §f!");

        $this->__destruct();
    }

    /**
     * Sets the Pokémon's location
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    public function getPokemonID(): int
    {
        return $this->pokemonID;
    }

    /**
     * Registers the Pokémon in the database
     */
    public function register(?PokemonData $data = null): void
    {
        PokemonManager::getInstance()->register($data ?? $this->getData());
    }

    public function getData(): ?PokemonData
    {
        try {
            return PokemonManager::getInstance()->getData($this->pokemonID);
        } catch (Throwable $th) {
            return null;
        }
    }

    /**
     * The parent's entity initialization function
     * @see https://github.com/pmmp/PocketMine-MP/blob/11f119551dec2771f0af8b9ae25e88a67d854b16/src/entity/Entity.php#L506
     */
    protected function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);
        if (($tag = $nbt->getTag("PokemonID")) !== null) {
            $this->pokemonID = $tag->getValue();
        }

        if (is_null($this->getData()) && !PokemonManager::getInstance()->isRegistered($this->pokemonID))
            PokemonManager::getInstance()->register($this->baseData);
    }

    /**
     * The parent's entity NBT saving function
     * @see https://github.com/pmmp/PocketMine-MP/blob/11f119551dec2771f0af8b9ae25e88a67d854b16/src/entity/Entity.php#L471
     */
    public function saveNBT(): CompoundTag
    {
        $nbt = parent::saveNBT();
        if (isset($this->pokemonID) && $this->pokemonID !== -1) {
            $nbt->setInt("PokemonID", $this->pokemonID);
        }
        return $nbt;
    }
}
// https://discord.com/channels/373199722573201408/480650036972224513/1223328956158054430