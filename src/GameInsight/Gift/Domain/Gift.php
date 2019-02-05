<?php
declare(strict_types=1);

namespace GameInsight\Gift\Domain;

class Gift
{
    protected $dbh;

    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
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
              (DEFAULT, :user_id, :day_id, :friend_id, :gift_id, 0, 1)');
        return $sth->execute([
            ':user_id' => $userId,
            ':day_id' => $dayId,
            ':friend_id' => $friendId,
            ':gift_id' => $giftId
        ]);
    }

    public function view(string $userId): array
    {
        $sth = $this->dbh->prepare('
            SELECT
              id,
              friend_id,
              gift_id
            FROM
              gifts
            WHERE
              user_id = :user_id
              AND is_valid = 1
              AND is_taken = 0');
        $sth->execute([':user_id' => $userId]);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
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
              AND `user_id` = :user_id
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
            ':day_id' => $currentDayId - 7,
        ]);
    }
}
