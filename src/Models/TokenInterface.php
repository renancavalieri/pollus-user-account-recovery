<?php declare(strict_types=1);

/**
 * User Account Recovery
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserAccountRecovery\Models;

interface TokenInterface 
{
    /**
     * Compares the input with the secret token.
     * 
     * This method SHOULD be constant time.
     * 
     * @param string $input
     * @param string $secret
     * 
     * @return bool
     */
    public function compare(string $input, string $secret) : bool;
    
    /**
     * Generates a unique cryptographically safe token
     * 
     * @param int $length
     * @return string
     */
    public function generate(int $length = 256) : string;
}
