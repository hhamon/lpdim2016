<?php

namespace Application\Repository;

class BlogPostRepository
{
    private $dbh;
    private $lastPostId;
    private $lastLastPostId;

    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    public function find($pk)
    {
        $query = <<<SQL
SELECT * FROM blog_post
WHERE (published_at iS NULL OR published_at <= NOW()) AND id = :id
SQL;

        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam('id', $pk, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getMostRecentPosts($limit)
    {
        $limit = (int) $limit;

        $query = <<<SQL
SELECT * FROM blog_post
WHERE published_at iS NULL or published_at <= NOW()
ORDER BY published_at DESC
LIMIT {$limit};
SQL;

        return $this->fetchAll($query);
    }


public function findAnyPost($pk)
    {
        $query = <<<SQL
SELECT * FROM blog_post
WHERE id = :id
SQL;

        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam('id', $pk, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function createBlogPosts($title, $content, $date=null)
    {

        $this->dbh->beginTransaction();

        $sql = <<<SQL
INSERT INTO blog_post (`title`, `content`, published_at)
VALUES  (:title, :content, :published_at)
SQL;

        $date = $date ? date("Y-m-d H:i:s", strtotime($date)) : date("Y-m-d H:i:s");

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam('title', $title, \PDO::PARAM_STR);
        $stmt->bindParam('content', $content, \PDO::PARAM_STR);
        $stmt->bindParam('published_at', $date, \PDO::PARAM_STR);

        $stmt->execute();

        $this->setLastPostId();

        try{
            return $this->dbh->commit();
        }catch(\PDOException $e){
            $this->dbh->rollBack();
            $this->resetPostId();
            //TODO Changing exception to custom exeption
            throw new \RuntimeException(sprintf('Error pending sql request, no changing effective. See : %s', $e->getMessage()));
        }
    }



    public function getLastPostId(){
        return $this->lastPostId;
    }

    private function setLastPostId(){
        $this->lastLastPostId = $this->lastPostId;
        $this->lastPostId = $this->dbh->lastInsertId();
    }

    private function resetPostId(){
        $this->lastPostId = $this->lastLastPostId;
    }



    private function fetchAll($sql)
    {
        return $this->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function query($sql)
    {
        return $this->dbh->query($sql);
    }
}
