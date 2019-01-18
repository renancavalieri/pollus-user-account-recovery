<?php declare(strict_types=1);

/**
 * User Account Recovery
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserAccountRecovery\Models;

use Pollus\UserAccountRecovery\Models\RequestInterface;
use Pollus\UserAccountRecovery\Exceptions\RequestRepositoryException;

interface RequestRepositoryInterface 
{
    /**
     * Creates a new reset request
     * 
     * @param mixed $user_id
     * @param string $token
     * return @bool
     */
    public function save($user_id, string $token) : bool;
    
    /**
     * Checks if the user have an pending request
     * 
     * @param type $user_id
     * @param int $timelimit
     * @return int
     * @throws RequestRepositoryException
     */
    public function hasPendingRequest($user_id, int $timelimit) : bool;
    
    /**
     * Selects a request by its user ID
     * 
     * @param mixed $user_id
     * @param int $timelimit
     * @throws RequestRepositoryException
     * @return RequestInterface
     */
    public function select($user_id, int $timelimit) : RequestInterface;
    
    /**
     * Deletes a request by its user ID
     * 
     * @param mixed $user_id
     * @return bool
     */
    public function delete($user_id) : bool;
}
