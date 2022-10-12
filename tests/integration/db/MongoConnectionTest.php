<?php

namespace tests\integration\SysKDB\db;

use PHPUnit\Framework\TestCase;

class MongoConnectionTest extends TestCase
{
    public function test1()
    {
        // print_r($_SERVER);
        // print_r(getenv(null));

        $this->assertTrue(true);

        $mongoUsername = getenv('MONGO_USERNAME');
        $mongoPassword = getenv('MONGO_PASSWORD');
        $mongoDatabase = getenv('MONGO_DATABASE');
        $mongoHost = getenv('MONGO_HOST');


        $client = new \MongoDB\Client("mongodb://$mongoUsername:$mongoPassword@$mongoHost:27017");
        $collection = $client->$mongoDatabase->objects;



        $record = [
            '_id' => 'aaaaaa' . time(),
            'oid' => 'abc'.time(),
            'data' => []
        ];

        $result = $collection->insertOne($record);
        $id = $result->getInsertedId();
        $this->assertNotFalse($id);



        $bulk = new \MongoDB\Driver\BulkWrite();

        // for ($i=0;$i<10;$i++) {
        //     $document = ['field_a'=>'a', 'tm'=>time()];
        //     $bulk->insert($document);
        // }

        $delete = [];
        $bulk->delete($delete);


        $client->getManager()->executeBulkWrite("${mongoDatabase}.objects", $bulk);
    }
}
