<?php

namespace Koenox\Data;

class FightData
{
    /** @var string $player1 */
    public readonly string $player1;

    /** @var string $player2 */
    public readonly string $player2;

    /** @var int $pokemon1 */
    public int $pokemon1;

    /** @var int $pokemon2 */
    public int $pokemon2;

    public function __construct(string $player1, string $player2, int $pokemon1, int $pokemon2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->pokemon1 = $pokemon1;
        $this->pokemon2 = $pokemon2;
    }
}