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
     * @param type $user_id
     * @param string $token
     * return @bool
     */
    public function save($user_id, string $token) : bool;
    
    /**
     * Invalidates a token
     * 
     * @param int $request_id
     * @return bool
     */
    public function invalidate(int $request_id) : bool;
    
    /**
     * Counts how many requests a user has generated within the last amount of
     * seconds specified by the "timelimit"
     * 
     * @param type $user_id
     * @param int $timelimit
     * @return int
     * @throws RequestRepositoryException
     */
    public function count($user_id, int $timelimit) : int;
    
    /**
     * Selects a request by its token
     * 
     * @param string $token
     * @param int $timelimit
     * @throws RequestRepositoryException
     * @return RequestInterface
     */
    public function select(string $token, int $timelimit) : RequestInterface;
    
    /**
     * Deletes a request by its ID
     * 
     * @param int $request_id
     * @return bool
     */
    public function delete(int $request_id) : bool;
}
