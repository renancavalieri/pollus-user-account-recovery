<?php declare(strict_types=1);

/**
 * User Account Recovery
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserAccountRecovery;

use Pollus\UserAccountRecovery\Models\RequestInterface;
use Pollus\UserAccountRecovery\Models\RequestRepositoryInterface;
use Pollus\UserAccountRecovery\Exceptions\RequestRepositoryException;
use Pollus\UserAccountRecovery\Models\TokenInterface;
use Pollus\UserAccountRecovery\Exceptions\RequestAlreadyExistsException;

class UserAccountRecovery 
{
    /**
     * @var TokenInterface
     */
    protected $token;
    
    /**
     * @var RequestRepositoryInterface
     */
    protected $repo;
    
    /**
     * @var int
     */
    protected $timelimit;
    
    /**
     * @var int
     */
    protected $token_length;
    
    /**
     * @param TokenInterface $token
     * @param RequestRepositoryInterface $repo
     * @param int $timelimit
     * @param int $token_length
     */
    public function __construct(TokenInterface $token, RequestRepositoryInterface $repo, int $timelimit = 600, int $token_length = 256) 
    {
        $this->token = $token;
        $this->repo = $repo;
        $this->timelimit = $timelimit;
        $this->token_length = $token_length;
    }
    
    /**
     * Creates a request and returns the token
     * 
     * @param type $user_id
     * @return string
     * @throws RequestAlreadyExistsException if a request already exists
     */
    public function createRequest($user_id) : string
    {
        if ($this->repo->count($user_id, $this->timelimit) >= 1)
        {
            throw new RequestAlreadyExistsException("A reset request for this user already exists");
        }
        $token = $this->token->generate($this->token_length);
        $this->repo->save($user_id, $token);
        return $token;
    }
    
    /**
     * Validates a request and invalidates it automatically.
     * 
     * Return TRUE if the request is valid
     * 
     * @param string $token
     * @return bool
     */
    public function validateRequest(string $token) : bool
    {
        try
        {
            $request = $this->repo->select($token, $this->timelimit);
            
            if ($this->token->compare($token, $request->getToken()) && $request->isValid())
            {
                $this->repo->invalidate($request->getId());
                return true;
            }
            
            return false;
        } 
        catch (RequestRepositoryException $ex) 
        {
            return false;
        }
    }
}
