<?php
declare(strict_types=1);

namespace GameInsight\Gift\Domain\Interfaces;

interface GiftInterface
{
    public function send(string $userId, int $dayId, string $friendId, int $giftId): bool;

    public function view(string $userId): array;

    public function take(string $userId, int $id): bool;

    public function expire(int $currentDayId): bool;
}
