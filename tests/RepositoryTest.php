<?php

use PHPUnit\Framework\TestCase;
use Pollus\UserAccountRecovery\Models\RequestRepository;
use PDO;

class RepositoryTest extends TestCase
{
    protected function setUp() 
    {
        require_once (__DIR__."/Helpers/Connection.php");
    }
    
    public function testCrudMethods()
    {
        $pdo = Connection::get();
        $repo = new RequestRepository($pdo);
        $this->assertSame(true, $repo->save(1, "test token"));
        $this->assertSame(true, $repo->hasPendingRequest(1, 600));
        $this->assertSame(true, $repo->save(1, "test token 2"));
        $this->assertSame(true, $repo->hasPendingRequest(1, 600));
        $request = $repo->select(1, 600);        
        $this->assertSame($request->getToken(), "test token 2");
        $repo->delete($request->getUserId());
        $this->assertSame(false, $repo->hasPendingRequest(1, 600));
    }
    
    
    public function testTimelimit()
    {
        $pdo = Connection::get();
        $repo = new RequestRepository($pdo);
        $this->assertSame(true, $repo->save(1, "new token"));
        $this->assertSame(true, $repo->hasPendingRequest(1, 600));
        sleep(2);
        $this->assertSame(false, $repo->hasPendingRequest(1, 1));
    }
}
