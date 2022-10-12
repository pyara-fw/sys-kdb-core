<?php

namespace SysKDB\kdb\repository\adapter;

use MongoDB\Client;
use MongoDB\Collection;
use SysKDB\kdb\repository\DataSet;
use SysKDB\lib\Constants;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Query;

class MongoAdapter implements AdapterInterface
{
    /**
     *
     *
     * @var Client
     */
    protected $client;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var string
     */
    protected $dbCollectionName;

    protected function getHoid($oid)
    {
        return hash('sha512', $oid);
    }

    /**
     * Stores the record on DB and returns its ID.
     *
     * @param array $object
     *
     * @return string Record ID
     */
    public function addObject(array $object): string
    {
        $data = [
            Constants::DATA => $object,
            Constants::OID => $object['oid'] ?? ''
        ];
        // if (isset($object[Constants::OID])) {
        // $object['hoid'] = $this->getHoid($object[Constants::OID]);
        // }
        $result = $this->getCollection()->insertOne($data);
        $oid = (string) $result->getInsertedId();
        return $oid;
    }

    /**
     * Seek on DB by an object with the given $oid.
     * If don't find, throws an Exception
     *
     * @param string $oid
     *
     * @return array
     */
    public function getObjectById(string $oid): array
    {
        // $hoid = $this->getHoid($oid);

        $filter = ['oid' => $oid];
        // $filter = ['hoid' => $hoid];
        $options = [
           //'projection' => ['_id' => 0],
        ];
        $query = new Query($filter, $options);

        $cursor = $this->getClient()
                    ->getManager()
                    ->executeQuery($this->getDbCollectionName(), $query);

        $cursor->setTypeMap(['root'=>'array', 'document' => 'array', Constants::DATA => 'array']);
        $rows = $cursor->toArray();


        $row = reset($rows);
        $result = (array) $row[Constants::DATA];

        return $result ?? [];
    }

    /**
     * Remove the object with the given $oid.
     * If don't find, throws an Exception
     *
     * @param string $oid
     *
     * @return void
     */
    public function removeObjectById(string $oid)
    {
    }

    /**
     * Update the object of the given $oid.
     *
     * @param string $oid
     * @param array $object
     *
     * @return void
     */
    public function updateObjectById(string $oid, array $object)
    {
    }

    /**
     * Get all objects
     *
     * @return DataSet
     */
    public function getAll(): DataSet
    {
        $filter = [];
        $options = [
           'projection' => ['_id' => 0],
        ];


        $query = new Query($filter, $options);



        $cursor = $this->getClient()
                    ->getManager()
                    ->executeQuery($this->getDbCollectionName(), $query);
        $cursor->setTypeMap(['root'=>'array', 'document' => 'array', Constants::DATA => 'array']);
        $rows = $cursor->toArray();

        $result = new DataSet();

        foreach ($rows as $row) {
            $arr = $row[Constants::DATA];
            $result->add($arr);
            // $result->add((array) $row[Constants::DATA]);
        }
        return $result;
    }

    /**
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return DataSet
     */
    public function findByKeyValueAttribute($key, $value): DataSet
    {
        $filter = [
            Constants::DATA . ".". $key => $value
        ];
        $options = [
           'projection' => ['_id' => 0],
        ];


        $query = new Query($filter, $options);



        $cursor = $this->getClient()
                    ->getManager()
                    ->executeQuery($this->getDbCollectionName(), $query);


        $cursor->setTypeMap(['root'=>'array', 'document' => 'array', Constants::DATA => 'array']);
        $rows = $cursor->toArray();


        $result = new DataSet();

        foreach ($rows as $row) {
            $result->add($row[Constants::DATA]);
        }
        return $result;
    }

    /**
     * Get the value of client
     *
     * @return  Client
     */
    public function getClient(): Client
    {
        if (!$this->client) {
            $mongoUsername = getenv('MONGO_USERNAME');
            $mongoPassword = getenv('MONGO_PASSWORD');
            $mongoHost = getenv('MONGO_HOST');
            $mongoDatabase = getenv('MONGO_DATABASE');
            $mongoCollection = $this->getMongoCollectionName();

            $this->client = new Client("mongodb://$mongoUsername:$mongoPassword@$mongoHost:27017");
            $this->collection = $this->client->$mongoDatabase->$mongoCollection;
        }
        return $this->client;
    }

    /**
     * Get the value of collection
     *
     * @return  Collection
     */
    public function getCollection(): Collection
    {
        if (!$this->collection) {
            $this->getClient();
        }
        return $this->collection;
    }

    /**
     * Get the value of dbCollectionName
     *
     * @return  string
     */
    public function getDbCollectionName()
    {
        if (!$this->dbCollectionName) {
            $mongoDatabase = getenv('MONGO_DATABASE');
            $mongoCollection = $this->getMongoCollectionName();

            $this->dbCollectionName = "$mongoDatabase.$mongoCollection";
        }
        return $this->dbCollectionName;
    }

    /**
     * @return string
     */
    protected function getMongoCollectionName(): string
    {
        $mongoCollection = getenv('MONGO_COLLECTION');
        if (!$mongoCollection) {
            $mongoCollection = 'objects';
        }
        return $mongoCollection;
    }
}
