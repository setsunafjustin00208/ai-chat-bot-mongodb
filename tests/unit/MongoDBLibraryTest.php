<?php

use PHPUnit\Framework\TestCase;
use App\Libraries\MongoDBLibrary;
use Psr\Log\LoggerInterface;

class MongoDBLibraryTest extends TestCase
{
    private $mongoDBLibrary;
    private $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->mongoDBLibrary = new MongoDBLibrary($this->logger);
    }

    public function testPingDatabase()
    {
        $this->logger->expects($this->once())
            ->method('info')
            ->with($this->equalTo("Pinged your deployment. You successfully connected to MongoDB!"));

        $this->mongoDBLibrary->pingDatabase();
    }

    public function testInsertOne()
    {
        $result = $this->mongoDBLibrary->insertOne('testDatabase', 'testCollection', ['name' => 'John Doe']);
        $this->assertNotNull($result, "InsertOne should return an inserted ID");
    }

    public function testInsertMany()
    {
        $documents = [
            ['name' => 'John Doe'],
            ['name' => 'Jane Doe']
        ];
        $result = $this->mongoDBLibrary->insertMany('testDatabase', 'testCollection', $documents);
        $this->assertNotNull($result, "InsertMany should return inserted IDs");
    }

    public function testFindOne()
    {
        $result = $this->mongoDBLibrary->findOne('testDatabase', 'testCollection', ['name' => 'John Doe']);
        $this->assertNotNull($result, "FindOne should return a document");
    }

    public function testFindMany()
    {
        $result = $this->mongoDBLibrary->findMany('testDatabase', 'testCollection', ['name' => 'John Doe']);
        $this->assertNotEmpty($result, "FindMany should return documents");
    }

    public function testUpdateOne()
    {
        $result = $this->mongoDBLibrary->updateOne('testDatabase', 'testCollection', ['name' => 'John Doe'], ['$set' => ['name' => 'John Smith']]);
        $this->assertGreaterThan(0, $result, "UpdateOne should return the number of modified documents");
    }

    public function testUpdateMany()
    {
        $result = $this->mongoDBLibrary->updateMany('testDatabase', 'testCollection', ['name' => 'John Doe'], ['$set' => ['name' => 'John Smith']]);
        $this->assertGreaterThan(0, $result, "UpdateMany should return the number of modified documents");
    }

    public function testDeleteOne()
    {
        $result = $this->mongoDBLibrary->deleteOne('testDatabase', 'testCollection', ['name' => 'John Smith']);
        $this->assertGreaterThan(0, $result, "DeleteOne should return the number of deleted documents");
    }

    public function testDeleteMany()
    {
        $result = $this->mongoDBLibrary->deleteMany('testDatabase', 'testCollection', ['name' => 'John Smith']);
        $this->assertGreaterThan(0, $result, "DeleteMany should return the number of deleted documents");
    }
}