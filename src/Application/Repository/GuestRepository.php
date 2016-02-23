<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 22/02/2016
 * Time: 22:42
 */

namespace Application\Repository;


use PDO;

class GuestRepository
{
    /**
     * @var PDO
     */
    private $dbh;

    public function __construct(PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * Search a guest with given params and fetch his rights
     * @param array $args
     * @return bool|mixed
     */
    public function auth(array $args = [])
    {
        $query = <<<SQL
SELECT id,username,password,created_at
FROM guest
WHERE username=:username
AND password=:password;
SQL;
        $stmt = $this->dbh->prepare($query);
        $stmt->execute($args);
        $auth = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!isset($auth['id'])){
            return false;
        }
        $auth['perms'] = $this->getRightsForAuth($auth['id']);
        return $auth;
    }

    /**
     * Create a new guest
     * @param array $args
     * @return bool|string
     */
    public function create(array $args = [])
    {
        $query = <<<SQL
INSERT into guest (username,password,created_at)
VALUES
(:username,:password,NOW());
SQL;
        $this->dbh->beginTransaction();
        $stmt = $this->dbh->prepare($query);
        try{
            $stmt->execute($args);
        }catch(\PDOException $e){
            $this->dbh->rollBack();
            return false;
        }
        if($stmt->rowCount()){
            $id = $this->dbh->lastInsertId('id');
            $this->dbh->commit();
            return $id;
        }
        $this->dbh->rollBack();
        return false;
    }

    /**
     * Return rights for a given guest
     * @param $id
     * @return array
     */
    private function getRightsForAuth($id)
    {
        $query = <<<SQL
SELECT p.label FROM guest_perms gp
JOIN perm p on p.id=gp.fk_perm
JOIN guest g on g.id = fk_guest
WHERE fk_guest=:id
SQL;
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}