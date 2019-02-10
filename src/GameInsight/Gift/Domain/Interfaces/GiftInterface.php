<?php
declare(strict_types=1);

namespace GameInsight\Gift\Domain\Interfaces;

/**
 * Interface GiftInterface
 * @package GameInsight\Gift\Domain\Interfaces
 */
interface GiftInterface
{
    /**
     * @param string $userId
     * @param int $dayId
     * @param string $friendId
     * @param int $giftId
     * @return bool
     */
    public function send(string $userId, int $dayId, string $friendId, int $giftId): bool;

    /**
     * @param string $userId
     * @return array
     */
    public function view(string $userId): array;

    /**
     * @param string $userId
     * @param int $id
     * @return bool
     */
    public function take(string $userId, int $id): bool;

    /**
     * @param int $currentDayId
     * @return bool
     */
    public function expire(int $currentDayId): bool;
}
