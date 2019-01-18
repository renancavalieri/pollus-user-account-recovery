<?php

use PHPUnit\Framework\TestCase;
use Pollus\UserAccountRecovery\Models\Token;

class TokenTest extends TestCase
{
    public function testTokenGenerateAndCompare()
    {
        $token = new Token();
        $secret = $token->generate();
        $this->assertSame(true, $token->compare($secret, $secret));
    }
    
    public function testTokenGenerateAndCompareInvalid()
    {
        $token = new Token();
        $secret = $token->generate();
        $this->assertSame(false, $token->compare("not a valid token", $secret));
    }
    
    public function testTokenLength()
    {
        $token = new Token();
        $this->assertSame(128, strlen($token->generate(128)));
        $this->assertSame(256, strlen($token->generate(256)));
        $this->assertSame(512, strlen($token->generate(512)));
        $this->assertSame(1024, strlen($token->generate(1024)));
    }
}
