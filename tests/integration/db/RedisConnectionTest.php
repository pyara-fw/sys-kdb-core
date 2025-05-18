<?php

namespace tests\integration\SysKDB\db;

use PHPUnit\Framework\TestCase;

class RedisConnectionTest extends TestCase
{
    public static $conn;
    public static $key;
    public static $value;

    public function test_connect_redis()
    {
        static::$conn = new \Redis();

        static::$conn->connect('redis');

        $this->assertTrue(true);
    }

    /**
     * @depends test_connect_redis
     *
     * @return void
     */
    public function test_add_key_to_redis()
    {
        static::$value = json_encode(['name' => 'John Doe', 'cod' => 1]);
        static::$key = 'salt-type-' . hash('sha256', static::$value);
        $return = static::$conn->set(static::$key, static::$value);

        $this->assertTrue($return);
    }

    /**
     * @depends test_add_key_to_redis
     *
     * @return void
     */
    public function test_get_value_via_key_on_redis()
    {
        $returnedValue = static::$conn->get(static::$key);
        $this->assertEquals(static::$value, $returnedValue);
    }

    /**
     * @depends test_get_value_via_key_on_redis
     *
     * @return void
     */
    public function test_delete_value_via_key_on_redis()
    {
        static::$conn->del(static::$key);

        $returnedValue = static::$conn->get(static::$key);
        $this->assertFalse($returnedValue);
    }
}
