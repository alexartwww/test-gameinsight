<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GameInsight\Gift\Config;

/**
 * Class ApiTest
 */
class ApiTest extends TestCase
{
    /**
     * @var string
     */
    protected $base_uri = 'http://nginx:80';
    /**
     * @var null
     */
    protected $client = null;
    /**
     * @var null
     */
    protected $nowDayId = null;
    /**
     * @var null
     */
    protected $expiredDayId = null;

    /**
     *
     */
    public function setUp()
    {
        $this->client = new Client([
            'base_uri' => $this->base_uri,
            'timeout' => 2.0,
            'http_errors' => false,
        ]);
        $this->nowDayId = intval(time() / 86400);
        $this->expiredDayId = intval(time() / 86400) - Config::$expireDays - 1;
    }

    /**
     * @param string $userId
     * @return array
     */
    public function view(string $userId): array
    {
        $response = $this->client->get('gifts/' . $userId, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getBody()->getContents());

        $this->assertEquals(0, $body->status);
        $this->assertTrue(isset($body->data));
        return $body->data;
    }

    /**
     * @param string $userId
     * @param int $dayId
     * @param string $friendId
     * @param int $giftId
     * @return mixed
     */
    public function send(string $userId, int $dayId, string $friendId, int $giftId)
    {
        $response = $this->client->post('gifts/' . $userId . '/' . $dayId, [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'X-Authorization' => Config::$auth,
            ],
            RequestOptions::JSON => [
                'friend_id' => $friendId,
                'gift_id' => $giftId,
            ],
        ]);
        $this->assertContains($response->getStatusCode(), [201, 400]);

        $body = json_decode($response->getBody()->getContents());

        $this->assertContains($body->status, [0, 1]);
        if ($body->status == 1) {
            $this->assertEquals("User " . $userId . " already send gift at day " . $dayId, $body->message);
        }
        return $body;
    }

    /**
     * @param string $userId
     * @param int $id
     * @return mixed
     */
    public function take(string $userId, int $id)
    {
        $response = $this->client->put('gifts/' . $userId, [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'X-Authorization' => Config::$auth,
            ],
            RequestOptions::JSON => [
                'id' => $id,
            ],
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getBody()->getContents());

        $this->assertEquals(0, $body->status);
        return $body;
    }

    /**
     * @param string $column
     * @param $value
     * @param array $data
     * @return mixed|null
     */
    public function findGiftBy(string $column, $value, array $data)
    {
        $foundGift = null;
        foreach ($data as $gift) {
            if ($gift->{$column} == $value) {
                $foundGift = $gift;
            }
        }
        return $foundGift;
    }

    /**
     *
     */
    public function testView()
    {
        $userId = 'Jimi-Hendrix';

        $data = $this->view($userId);
        $gift = $this->findGiftBy('id', 5, $data);

        $this->assertNotEquals(null, $gift);
        $this->assertEquals(5, $gift->id);
        $this->assertEquals('Paul-Gilbert', $gift->friend_id);
        $this->assertEquals(5, $gift->gift_id);
    }

    /**
     *
     */
    public function testSend()
    {
        $userId = 'Jimi-Hendrix';
        $dayId = $this->nowDayId;
        $friendId = 'Slash';
        $giftId = time();

        $sendResult = $this->send($userId, $dayId, $friendId, $giftId);
        if ($sendResult->status == 1) {
            $this->markTestSkipped('Please restore original database to run API test');
        }

        $data = $this->view($friendId);
        $this->assertGreaterThan(0, count($data));
        $gift = $this->findGiftBy('gift_id', $giftId, $data);
        $this->assertNotEquals(null, $gift);
    }

    /**
     *
     */
    public function testSendExpired()
    {
        $userId = 'Brian-May';
        $dayId = $this->expiredDayId;
        $friendId = 'Paul-Gilbert';
        $giftId = time();

        $this->send($userId, $dayId, $friendId, $giftId);

        $data = $this->view($friendId);
        $this->assertGreaterThan(0, count($data));
        $gift = $this->findGiftBy('gift_id', $giftId, $data);
        $this->assertEquals(null, $gift);
    }


    /**
     *
     */
    public function testBlockSendTwice()
    {
        $userId = 'Paul-Gilbert';
        $dayId = $this->nowDayId;
        $friendId = 'Slash';
        $giftId = time();

        $this->send($userId, $dayId, $friendId, $giftId);

        $data = $this->view($friendId);
        $this->assertGreaterThan(0, count($data));
        $gift = $this->findGiftBy('gift_id', $giftId, $data);
        $this->assertNotEquals(null, $gift);

        $sendResult = $this->send($userId, $dayId, $friendId, $giftId);

        $this->assertEquals(1, $sendResult->status);
        $this->assertEquals("User " . $userId . " already send gift at day " . $dayId, $sendResult->message);
    }

    /**
     *
     */
    public function testTake()
    {
        $userId = 'Brian-May';
        $dayId = $this->nowDayId;
        $friendId = 'Paul-Gilbert';
        $giftId = time();

        $sendResult = $this->send($userId, $dayId, $friendId, $giftId);
        if ($sendResult->status == 1) {
            $this->markTestSkipped('Please restore original database to run API test');
        }
        $data = $this->view($friendId);
        $this->assertGreaterThan(0, count($data));
        $gift = $this->findGiftBy('gift_id', $giftId, $data);
        $this->assertNotEquals(null, $gift);
        $this->take($friendId, $gift->id);

        $data = $this->view($friendId);
        $gift = $this->findGiftBy('gift_id', $giftId, $data);
        $this->assertEquals(null, $gift);

    }
}
