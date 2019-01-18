<?php declare(strict_types=1);

/**
 * User Account Recovery
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserAccountRecovery\Models;

use PDO;
use Pollus\UserAccountRecovery\Models\RequestRepositoryInterface;
use Pollus\UserAccountRecovery\Models\RequestInterface;
use Pollus\UserAccountRecovery\Models\Request;
use Pollus\UserAccountRecovery\Exceptions\RequestRepositoryException;

class RequestRepository implements RequestRepositoryInterface
{
    /**
     * @var PDO
     */
    protected $pdo;
    
    /**
     * @var string
     */
    protected $model;
    
    /**
     * @var string
     */
    protected $table;
    
    /**
     * @param PDO $pdo
     * @param string $table
     * @param string $model
     */
    public function __construct(PDO $pdo, string $table = "reset_requests", string $model = Request::class) 
    {
        $this->pdo = $pdo;
        $this->model = $model;
        $this->table = $table;
    }
    
    /**
     * {@inheritDoc}
     */
    public function hasPendingRequest($user_id, int $timelimit): bool 
    {
        $sql = "SELECT count(user_id) FROM {$this->table} "
             . "WHERE user_id = :user_id "
             . "AND generated_at > :generated_at";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':generated_at', $this->subSecDate($timelimit));
        
        if ($stmt->execute())
        {
            return (bool) $stmt->fetchColumn(0);
        }
        
        throw new RequestRepositoryException("Statement execute failed on count");
    }

    /**
     * {@inheritDoc}
     */
    public function delete($user_id) : bool
    {
        $sql = "DELETE FROM {$this->table} "
             . "WHERE user_id = :request_id ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':request_id', $user_id);
        
        return $stmt->execute();
    }

 
    /**
     * {@inheritDoc}
     */
    public function save($user_id, string $token) : bool
    {
        $sql = "REPLACE INTO {$this->table} "
             . "    (user_id, token, generated_at) "
             . "VALUES"
             . "    (:user_id, :token, :generated_at) ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':generated_at', date('Y-m-d H:i:s'));
        
        return $stmt->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function select($user_id, int $timelimit): RequestInterface 
    {
        $sql = "SELECT * FROM {$this->table} "
             . "WHERE user_id = :token "
             . "AND generated_at > :generated_at";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':token', $user_id);
        $stmt->bindValue(':generated_at', $this->subSecDate($timelimit));
        
        if ($stmt->execute() === false)
        {
            throw new RequestRepositoryException("Statement execute failed on select");
        }
        
        $result = $stmt->fetchObject($this->model);
        
        if (empty($result) || $result === null)
        {
            throw new RequestRepositoryException("Expected 1 row but none returned");
        }
        else if (is_array($result))
        {
            throw new RequestRepositoryException("Expected only 1 row, multiple rows returned");
        }
        
        return $result;
    }
    
    /**
     * Subtract seconds from a given date
     * 
     * @param int $seconds
     * @return string
     */
    protected function subSecDate(int $seconds) : string
    {
        return date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s')) - $seconds);
    }
}
