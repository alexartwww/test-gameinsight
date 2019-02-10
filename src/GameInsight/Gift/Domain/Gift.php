<?php
declare(strict_types=1);

namespace GameInsight\Gift\Domain;

use GameInsight\Gift\Domain\Interfaces\GiftInterface;
use GameInsight\Gift\Domain\Exceptions\GiftException;

/**
 * Class Gift
 * @package GameInsight\Gift\Domain
 */
class Gift implements GiftInterface
{
    /**
     * @var
     */
    protected $dbh;
    /**
     * @var int
     */
    protected $expireDay = 7;

    /**
     * Gift constructor.
     * @param $dbh
     * @param int $expireDay
     */
    public function __construct($dbh, int $expireDay)
    {
        $this->dbh = $dbh;
        $this->expireDay = $expireDay;
    }

    /**
     * @param string $userId
     * @param int $dayId
     * @return bool
     */
    public function checkSendAbility(string $userId, int $dayId)
    {
        $sth = $this->dbh->prepare('
            SELECT
              COUNT(`id`) AS `num`
            FROM
              `gifts`
            WHERE
              `user_id` = :user_id
              AND `day_id` = :day_id');
        $sth->execute([':user_id' => $userId, ':day_id' => $dayId]);
        return $sth->fetchColumn() == 0;
    }

    /**
     * @param string $userId
     * @param int $dayId
     * @param string $friendId
     * @param int $giftId
     * @return bool
     * @throws GiftException
     */
    public function send(string $userId, int $dayId, string $friendId, int $giftId): bool
    {
        if (!$this->checkSendAbility($userId, $dayId)) {
            throw new GiftException('User ' . $userId . ' already send gift at day ' . $dayId);
        }
        $sth = $this->dbh->prepare('
            INSERT INTO `gifts` (
              `id`,
              `user_id`,
              `day_id`,
              `friend_id`,
              `gift_id`,
              `is_taken`,
              `is_valid`
            )
            VALUES
              (DEFAULT, :user_id, :day_id, :friend_id, :gift_id, :is_taken, :is_valid)');
        return $sth->execute([
            ':user_id' => $userId,
            ':day_id' => $dayId,
            ':friend_id' => $friendId,
            ':gift_id' => $giftId,
            ':is_taken' => 0,
            ':is_valid' => ($dayId >= intval(time() / 86400) - $this->expireDay) ? 1 : 0,
        ]);
    }

    /**
     * @param string $userId
     * @return array
     */
    public function view(string $userId): array
    {
        $sth = $this->dbh->prepare('
            SELECT
              `id`,
              `user_id` AS `friend_id`,
              `gift_id`
            FROM
              `gifts`
            WHERE
              `friend_id` = :user_id
              AND `is_valid` = 1
              AND `is_taken` = 0');
        $sth->execute([':user_id' => $userId]);
        $result = [];
        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $row['id'] = intval($row['id']);
            $row['gift_id'] = intval($row['gift_id']);
            $result[] = $row;
        }
        return $result;
    }

    /**
     * @param string $userId
     * @param int $id
     * @return bool
     */
    public function take(string $userId, int $id): bool
    {
        $sth = $this->dbh->prepare('
            UPDATE
              `gifts`
            SET
              `is_taken` = 1
            WHERE
              `id` = :id
              AND `friend_id` = :user_id
              AND `is_taken` = 0
              AND `is_valid` = 1');
        return $sth->execute([
            ':id' => $id,
            ':user_id' => $userId
        ]);
    }

    /**
     * @param int $currentDayId
     * @return bool
     */
    public function expire(int $currentDayId): bool
    {
        $sth = $this->dbh->prepare('
            UPDATE
              `gifts`
            SET
              `is_valid` = 0
            WHERE
              `day_id` < :day_id
              AND `is_taken` = 0
              AND `is_valid` = 1');
        return $sth->execute([
            ':day_id' => $currentDayId - $this->expireDay,
        ]);
    }
}
