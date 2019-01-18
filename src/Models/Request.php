<?php declare(strict_types=1);

/**
 * User Account Recovery
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserAccountRecovery\Models;

use Pollus\UserAccountRecovery\Models\RequestInterface;

class Request implements RequestInterface
{
    protected $id;
    protected $user_id;
    protected $token;
    protected $generated_at;
    protected $valid;
    
    /**
     * {@inheritDoc}
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getToken(): string 
    {
        return $this->token;
    }

    /**
     * {@inheritDoc}
     */
    public function getGenerateDate(): string 
    {
        return $this->generated_at;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserId() 
    {
        return $this->user_id;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(): bool 
    {
        return (bool) $this->valid;
    }
}
