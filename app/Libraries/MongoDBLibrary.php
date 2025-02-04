<?php

namespace App\Libraries;

use MongoDB\Client;
use MongoDB\Driver\ServerApi;
use Exception;
use Psr\Log\LoggerInterface;

class MongoDBLibrary
{
    private $client;
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        $hostname = getenv('mongo.default.hostname');
        $appName = getenv('mongo.default.appName');
        $username = getenv('mongo.default.username');
        $password = getenv('mongo.default.password');

        $uri = "mongodb+srv://$username:$password@$hostname/?retryWrites=true&w=majority&appName=$appName";

        $apiVersion = new ServerApi(ServerApi::V1);
        $this->client = new Client($uri, [], ['serverApi' => $apiVersion]);

        $this->pingDatabase();
    }

    public function pingDatabase()
    {
        try {
            // Send a ping to confirm a successful connection
            $this->client->selectDatabase('admin')->command(['ping' => 1]);
            $this->logger->info("Pinged your deployment. You successfully connected to MongoDB!");
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    public function getClient()
    {
        return $this->client;
    }

    public function insertOne($database, $collection, $document)
    {
        try {
            $result = $this->client->selectCollection($database, $collection)->insertOne($document);
            return $result->getInsertedId();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function insertMany($database, $collection, $documents)
    {
        try {
            $result = $this->client->selectCollection($database, $collection)->insertMany($documents);
            return $result->getInsertedIds();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function findOne($database, $collection, $filter = [], $options = [])
    {
        try {
            return $this->client->selectCollection($database, $collection)->findOne($filter, $options);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function findMany($database, $collection, $filter = [], $options = [])
    {
        try {
            return $this->client->selectCollection($database, $collection)->find($filter, $options)->toArray();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function updateOne($database, $collection, $filter, $update, $options = [])
    {
        try {
            $result = $this->client->selectCollection($database, $collection)->updateOne($filter, $update, $options);
            return $result->getModifiedCount();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function updateMany($database, $collection, $filter, $update, $options = [])
    {
        try {
            $result = $this->client->selectCollection($database, $collection)->updateMany($filter, $update, $options);
            return $result->getModifiedCount();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function deleteOne($database, $collection, $filter, $options = [])
    {
        try {
            $result = $this->client->selectCollection($database, $collection)->deleteOne($filter, $options);
            return $result->getDeletedCount();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function deleteMany($database, $collection, $filter, $options = [])
    {
        try {
            $result = $this->client->selectCollection($database, $collection)->deleteMany($filter, $options);
            return $result->getDeletedCount();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }
}