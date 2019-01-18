<?php declare(strict_types=1);

/**
 * User Account Recovery
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserAccountRecovery\Models;

use Pollus\UserAccountRecovery\Models\TokenInterface;

class Token implements TokenInterface
{
    /**
     * {@inheritDoc}
     */
    public function compare(string $input, string $secret) : bool
    {
        return hash_equals($input, $secret);
    }

    /**
     * {@inheritDoc}
     */
    public function generate(int $length = 256): string 
    {
        $uniqid = uniqid();
        $strlen = $length - strlen($uniqid) - 1;
        $secret = base64_encode( random_bytes( (int) (($strlen*3)/4) ));
        return substr($uniqid.$secret, 0, $length);
    }

}
