<?php

namespace Application\Repository;

class BlogPostRepository
{
    private $dbh;

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

    public function edit($pk,array $args = [])
    {
        $query = <<<SQL
UPDATE blog_post SET title=:title, content=:content, content_markdown=:content_markdown WHERE id=:id
SQL;
        $this->dbh->beginTransaction();
        $stmt = $this->dbh->prepare($query);
        $args = array_merge(['id'=>$pk],$args);

        try{
            $stmt->execute($args);
        }catch(\PDOException $e){
            $this->dbh->rollBack();
            return false;
        }
        if($stmt->rowCount()){
            $this->dbh->commit();
            return true;
        }
        $this->dbh->rollBack();
        return false;
    }
    public function create(array $args = [])
    {
        $query = <<<SQL
INSERT into blog_post (title,content,content_markdown,published_at)
VALUES
(:title,:content,:content_markdown,NOW());
SQL;
        $this->dbh->beginTransaction();
        $stmt = $this->dbh->prepare($query);
        try{
            $stmt->execute($args);
        }catch(\PDOException $e){
            $this->dbh->rollBack();
        }
        if($stmt->rowCount()){
            $id = $this->dbh->lastInsertId();
            $this->dbh->commit();
            return $id;
        }
        $this->dbh->rollBack();
        throw new \RuntimeException('Une erreur c\'est produite lors de l\'insertion');
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
