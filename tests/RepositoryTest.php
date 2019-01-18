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
        $this->assertSame(1, $repo->count(1, 600));
        $this->assertSame(true, $repo->save(1, "test token 2"));
        $this->assertSame(2, $repo->count(1, 600));
        $request = $repo->select("test token", 600);        
        $this->assertSame($request->getToken(), "test token");
        $repo->delete($request->getId());
        $this->assertSame(1, $repo->count(1, 600));
    }
    
    public function testInvalidate()
    {
        $pdo = Connection::get();
        $repo = new RequestRepository($pdo);
        $this->assertSame(true, $repo->save(1, "new token"));
        $request = $repo->select("new token", 600);        
        $this->assertSame(true, $request->isValid());
        $repo->invalidate($request->getId());
        $request = $repo->select("new token", 600);        
        $this->assertSame(false, $request->isValid());
    }
    
    public function testTimelimit()
    {
        $pdo = Connection::get();
        $repo = new RequestRepository($pdo);
        $this->assertSame(true, $repo->save(1, "new token"));
        $this->assertSame(1, $repo->count(1, 600));
        sleep(2);
        $this->assertSame(0, $repo->count(1, 1));
    }
}
