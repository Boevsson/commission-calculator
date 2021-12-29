<?php

declare(strict_types=1);

namespace Boevsson\CommissionTask\Models;

use Exception;

class UserFactory
{
    /**
     * @throws Exception
     */
    public function createUser(int $userId, string $userType)
    {
        switch ($userType) {
            case 'private':
                return new PrivateUser($userId);
            case 'business':
                return new BusinessUser($userId);
            default:
                throw new Exception('Error! Unknown user type');
        }
    }
}
