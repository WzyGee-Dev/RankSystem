<?php

namespace RankSystem\Rank\interface;

interface RankRepositoryInterface
{
public function loadRanks(): array;

public function saveRanks(array $ranks): void;
}