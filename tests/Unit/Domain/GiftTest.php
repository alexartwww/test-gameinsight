<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Domain\Gift;

/**
 * Class GiftTest
 */
class GiftTest extends TestCase
{
    /**
     * @throws \GameInsight\Gift\Domain\Exceptions\GiftException
     */
    public function testSend()
    {
        $userId = 'Slash';
        $dayId = intval(time() / 86400) - 4;
        $friendId = 'Jimi-Hendrix';
        $giftId = 12345;
        $expireDay = 7;
        $sqlCheck = '
            SELECT
              COUNT(`id`) AS `num`
            FROM
              `gifts`
            WHERE
              `user_id` = :user_id
              AND `day_id` = :day_id';
        $sqlInsert = '
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
              (DEFAULT, :user_id, :day_id, :friend_id, :gift_id, :is_taken, :is_valid)';

        $sth = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\PDOStatementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sth->expects($this->at(0))
            ->method('execute')
            ->with($this->equalTo([':user_id' => $userId, ':day_id' => $dayId]))
            ->willReturn(true);
        $sth->expects($this->at(1))
            ->method('execute')
            ->with($this->equalTo([
                ':user_id' => $userId,
                ':day_id' => $dayId,
                ':friend_id' => $friendId,
                ':gift_id' => $giftId,
                ':is_taken' => 0,
                ':is_valid' => ($dayId >= intval(time() / 86400) - $expireDay) ? 1 : 0,
            ]))
            ->willReturn(true);
        $sth->expects($this->at(0))
            ->method('fetchColumn')
            ->willReturn(1);
        $sth->expects($this->at(0))
            ->method('fetch')
            ->with($this->equalTo(\PDO::FETCH_ASSOC))
            ->willReturn(['id' => '123', 'friend_id' => 'Jimi-Hendrix', 'gift_id' => '12345']);
        $sth->expects($this->at(1))
            ->method('fetch')
            ->with($this->equalTo(\PDO::FETCH_ASSOC))
            ->willReturn(false);

        $dbh = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\PDOInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dbh->expects($this->at(0))
            ->method('prepare')
            ->with($this->equalTo($sqlCheck))
            ->willReturn($sth);
        $dbh->expects($this->at(1))
            ->method('prepare')
            ->with($this->equalTo($sqlInsert))
            ->willReturn($sth);

        $gift = new Gift($dbh, $expireDay);
        $result = $gift->send($userId, $dayId, $friendId, $giftId);

        $this->assertTrue($result);
    }

    /**
     *
     */
    public function testView()
    {
        $userId = 'Slash';
        $expireDay = 7;
        $sql = '
            SELECT
              `id`,
              `user_id` AS `friend_id`,
              `gift_id`
            FROM
              `gifts`
            WHERE
              `friend_id` = :user_id
              AND `is_valid` = 1
              AND `is_taken` = 0';
        $sth = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\PDOStatementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sth->expects($this->once())
            ->method('execute')
            ->with([':user_id' => $userId])
            ->willReturn(true);
        $sth->expects($this->at(0))
            ->method('fetch')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn(['id' => '123', 'friend_id' => 'Jimi-Hendrix', 'gift_id' => '12345']);

        $dbh = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\PDOInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dbh->expects($this->once())
            ->method('prepare')
            ->with($sql)
            ->willReturn($sth);

        $gift = new Gift($dbh, $expireDay);
        $result = $gift->view($userId);

        $this->assertEquals([['id' => 123, 'friend_id' => 'Jimi-Hendrix', 'gift_id' => 12345]], $result);
    }

    /**
     *
     */
    public function testTake()
    {
        $userId = 'Slash';
        $id = 12345;
        $expireDay = 7;
        $sql = '
            UPDATE
              `gifts`
            SET
              `is_taken` = 1
            WHERE
              `id` = :id
              AND `friend_id` = :user_id
              AND `is_taken` = 0
              AND `is_valid` = 1';
        $sth = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\PDOStatementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sth->method('execute')->willReturn(true);
        $sth->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([
                ':id' => $id,
                ':user_id' => $userId
            ]));

        $dbh = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\PDOInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dbh->method('prepare')->willReturn($sth);
        $dbh->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo($sql));

        $gift = new Gift($dbh, $expireDay);
        $result = $gift->take($userId, $id);

        $this->assertTrue($result);
    }
}
