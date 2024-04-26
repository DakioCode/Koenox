<?php

namespace Koenox\View\pokemon;

use jojoe77777\FormAPI\SimpleForm;
use Koenox\Data\PokemonData;
use Koenox\Koenox;
use pocketmine\player\Player;

class PokemonInfo extends SimpleForm
{
    public function __construct(PokemonData $data)
    {
        parent::__construct(function(Player $player, int $data = null) {
            switch ($data) {
                case null:
                    return true;
                    break;
            }
        });

        $content = [
            "Voici les informations à propos de ce Pokémon :",
            "§dNom : §f" . mb_convert_case(str_replace("_", " ", $data->identifier), MB_CASE_TITLE_SIMPLE),
            "§4PV : §f" . (string) $data->health,
            "§eDresseur(euse) : §f" . $data->dressor === "" ? "aucun" : $data->dressor,
        ];

        if ($data->shiny)
            $content[] = "§c§kI§r §9Ce Pokémon est §lshiny §r§c§kI";

        $this->setTitle("Informations sur un Pokémon");
        $this->setContent(Koenox::getInstance()->arrayToString($content));
    }
}