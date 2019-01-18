<?php

use PHPUnit\Framework\TestCase;
use Pollus\UserAccountRecovery\Models\RequestRepository;
use PDO;
use Pollus\UserAccountRecovery\UserAccountRecovery;
use Pollus\UserAccountRecovery\Models\Token;
use Pollus\UserAccountRecovery\Exceptions\RequestAlreadyExistsException;

class UserAccountRecoveryTest extends TestCase
{
    protected function setUp() 
    {
        require_once (__DIR__."/Helpers/Connection.php");
    }
    
    public function testNormalUsage()
    {
        $pdo = Connection::get();
        $recovery = new UserAccountRecovery(new Token(), new RequestRepository($pdo), 600);
        $token = $recovery->createRequest(1);
        $this->assertSame(true, $recovery->validateRequest($token));
        $this->assertSame(false, $recovery->validateRequest($token));
    }
    
    public function testDuplicateTokenException()
    {
        $this->expectException(RequestAlreadyExistsException::class);
        $pdo = Connection::get();
        $repo = new RequestRepository($pdo);
        $token = new Token();
        $recovery = new UserAccountRecovery($token, $repo, 600);
        $token = $recovery->createRequest(1);
        $token = $recovery->createRequest(1);
    }
    
   
}
