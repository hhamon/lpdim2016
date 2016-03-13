<?php

namespace Framework\Persistence;

abstract class AbstractRepository
{
    private $dbh;

    /**
     * Constructor.
     *
     * @param \PDO $dbh
     */
    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    protected function executeTransaction(callable $callback)
    {
        try {
            $this->dbh->beginTransaction();
            $out = call_user_func_array($callback, []);
            $this->dbh->commit();
        } catch (\PDOException $e) {
            $this->dbh->rollBack();
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }

        return $out;
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function prepare($sql, array $options = [])
    {
        return $this->dbh->prepare($sql, $options);
    }

    /**
     * Sets the entity's primary key using a PHP Reflection trick.
     *
     * @param object     $entity  The entity instance to modify
     * @param int|string $pk      The entity primary key value
     * @param string     $pkField The entity primary key field name
     */
    protected static function populateEntityPk($entity, $pk, $pkField = 'id')
    {
        if (!is_object($entity)) {
            throw new \InvalidArgumentException('The entity must be a valid object.');
        }

        $ro = new \ReflectionObject($entity);
        $rp = $ro->getProperty($pkField);
        $rp->setAccessible(true);
        $rp->setValue($entity, $pk);
    }
}
