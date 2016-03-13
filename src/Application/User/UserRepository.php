<?php

namespace Application\User;

use Framework\Persistence\AbstractRepository;
use Framework\Security\User\UserNotFoundException;
use Framework\Security\User\UserProviderInterface;

class UserRepository extends AbstractRepository implements UserProviderInterface
{
    private $selectOneUserByUsernameStmt;
    private $insertOneUserStmt;

    private static function asArray(User $user)
    {
        return [
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'salt' => $user->getSalt(),
            'permissions' => implode(',', $user->getPermissions()),
            'registrationDate' => $user->getRegistrationDate(),
        ];
    }

    /**
     * Lazy loads the \PDOStatement instance to insert a new blog post.
     *
     * @return \PDOStatement
     */
    private function insertOneUserStatement()
    {
        if ($this->insertOneUserStmt) {
            return $this->insertOneUserStmt;
        }

        $query = <<<SQL
INSERT INTO `user_account`
(`username`, `salt`, `password`, `permissions`, `registration_date`)
VALUES (:username, :salt, :password, :permissions, :registrationDate);
SQL;

        $this->insertOneUserStmt = $this->prepare($query);

        return $this->insertOneUserStmt;
    }

    /**
     * Saves a user account to the database.
     *
     * @param User $user
     * @throws \Exception
     */
    public function save(User $user)
    {
        $id = $this->executeTransaction(function () use ($user) {
            $stmt = $this->insertOneUserStatement();
            $stmt->execute(static::asArray($user));

            return (int) $this->lastInsertId();
        });

        static::populateEntityPk($user, $id);
    }

    public function loadUserByUsername($username)
    {
        $stmt = $this->selectOneUserByUsernameStatement();
        $stmt->execute(['username' => $username]);

        if (!$record = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            throw new UserNotFoundException(sprintf('Unable to find user with username "%s".', $username));
        }

        return $this->hydrateObject($record);
    }

    private function hydrateObject(array $record)
    {
        $user = User::fromArray($record);

        if (!empty($record['id'])) {
            static::populateEntityPk($user, $record['id']);
        }

        return $user;
    }

    private function selectOneUserByUsernameStatement()
    {
        if ($this->selectOneUserByUsernameStmt) {
            return $this->selectOneUserByUsernameStmt;
        }

        $query = 'SELECT * FROM `user_account` WHERE `username` = :username LIMIT 1;';
        $this->selectOneUserByUsernameStmt = $this->prepare($query);

        return $this->selectOneUserByUsernameStmt;
    }
}
