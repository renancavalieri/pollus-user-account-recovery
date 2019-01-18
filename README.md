# Pollus - User Account Recovery

This library provides methods to generate account recovery requests for Pollus Framework.

## Usage

Install with composer:

```composer require pollus/user-account-recovery```

Create the following table:

```sql
CREATE TABLE IF NOT EXISTS `reset_requests` (
  `user_id` int(11) NOT NULL,
  `token` varchar(512) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
);
```

## Examples

**Creating a new request**

```php
use Pollus\UserAccountRecovery\Models\RequestRepository;
use Pollus\UserAccountRecovery\UserAccountRecovery;
use Pollus\UserAccountRecovery\Models\Token;
use Pollus\UserAccountRecovery\Exceptions\RequestAlreadyExistsException;

$pdo = new \PDO("mysql:host=127.0.0.1;dbname=DATABASE_NAME", $user, $password, 
           [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ]);
				
$recovery = new UserAccountRecovery(new Token(), new RequestRepository($pdo), 600);

try
{
    $token = $recovery->createRequest($user_id);
    // Send the token by email
}
catch(RequestAlreadyExistsException $ex)
{
    // Request already exists
}
;;
```

**Validating a request**
```php
use Pollus\UserAccountRecovery\Models\RequestRepository;
use Pollus\UserAccountRecovery\UserAccountRecovery;
use Pollus\UserAccountRecovery\Models\Token;

$pdo = new \PDO("mysql:host=127.0.0.1;dbname=DATABASE_NAME", $user, $password, 
           [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ]);
				
$recovery = new UserAccountRecovery(new Token(), new RequestRepository($pdo), 600);

// [..] gets the token and the user ID

if ($recovery->validateRequest($user_id, $token))
{
    // login the user and force a password change
    // the token will be automatically invalidate
}
else
{
    // invalid token
}
;;
```
### MIT License

Copyright (c) 2019 Renan Cavalieri

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.