<?php

namespace App\MyAuthenticator;

use Nette;
use Nette\Security\SimpleIdentity;

readonly class MyAuthenticator implements Nette\Security\Authenticator
{
    public function __construct(
        private Nette\Database\Explorer  $database,
        private Nette\Security\Passwords $passwords,
    ) {
    }

    public function authenticate(string $user, string $password): SimpleIdentity
    {
        $userInfo = $this->database->table('users')
            ->select('user_id, login, email, password')
            ->where('login', $user)
            ->fetch();
        
        if (!$userInfo) {
            throw new Nette\Security\AuthenticationException('User not found.');
        }

        if (!$this->passwords->verify($password, $userInfo->password)) {
            throw new Nette\Security\AuthenticationException(
                'Invalid password.'
            );
        }

        $roles = $this->database
            ->query(/** @lang text */ 'SELECT t1.group_id, t1.group_name
                         FROM `groups` t1
                         LEFT JOIN user_groups t2 ON t1.group_id = t2.group_id
                         LEFT JOIN users u on u.user_id = t2.user_id
                         WHERE u.user_id = ?;', $userInfo->user_id);

        $roles = $roles->fetchPairs('group_id', 'group_name');

        return new SimpleIdentity(
            $userInfo->user_id,
            $roles,
            [
                'login' => $userInfo->login,
                'email' => $userInfo->email,
            ]
        );
    }
}
