<?php

namespace Koenox\Model\attack;

use Koenox\Custom\pokemon\Pokemon;
use Koenox\Data\AttackData;

class Attack
{
    protected AttackData $data;
    protected Pokemon $damager;
    protected Pokemon $victim;

    public function __construct(AttackData $data, Pokemon $damager, Pokemon $victim)
    {
        $this->data = $data;
    }

    public function getData(): AttackData
    {
        return $this->data;
    }

    public function getDamager(): Pokemon
    {
        return $this->damager;
    }

    public function getVictim(): Pokemon
    {
        return $this->victim;
    }

    public function play(): void
    {
        $this->victim->setHealthPoints($this->victim->getHealthPoints() - $this->data->damages);
    }
}