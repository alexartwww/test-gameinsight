<?php
declare(strict_types=1);

namespace GameInsight\Gift\Domain;

class Gift
{
    protected $dbh;
    protected $expireDay = 7;

    public function __construct(\PDO $dbh, int $expireDay)
    {
        $this->dbh = $dbh;
        $this->expireDay = $expireDay;
    }

    public function setExpireDay(int $expireDay): Gift
    {
        $this->expireDay = $expireDay;
        return $this;
    }

    public function send(string $userId, int $dayId, string $friendId, int $giftId): bool
    {
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
            ':is_valid' => ($dayId >= intval(time()/86400) - $this->expireDay) ? 1 : 0,
        ]);
    }

    public function view(string $userId): array
    {
        $sth = $this->dbh->prepare('
            SELECT
              `id`,
              `user_id` AS friend_id,
              `gift_id`
            FROM
              `gifts`
            WHERE
              `friend_id` = :user_id
              AND `is_valid` = 1
              AND `is_taken` = 0');
        $sth->execute([':user_id' => $userId]);
        $result = [];
        while($row = $sth->fetch (\PDO::FETCH_ASSOC)) {
            $row['id'] = intval($row['id']);
            $row['gift_id'] = intval($row['gift_id']);
            $result[] = $row;
        }
        return $result;
    }

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
