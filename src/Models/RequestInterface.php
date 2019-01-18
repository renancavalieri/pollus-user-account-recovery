<?php declare(strict_types=1);

/**
 * User Account Recovery
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserAccountRecovery\Models;

interface RequestInterface 
{
    /**
     * Returns the request ID
     * 
     * @return int
     */
    public function getId() : int;
    
    /**
     * Returns the request token
     * 
     * @return string
     */
    public function getToken() : string;
    
    /**
     * Returns TRUE if the request is valid
     * 
     * @return bool
     */
    public function isValid() : bool;
    
    /**
     * Returns token's generated date
     * 
     * @return string
     */
    public function getGenerateDate() : string;
    
    /**
     * Returns the user ID
     * 
     * @return mixed
     */
    public function getUserId();
}
