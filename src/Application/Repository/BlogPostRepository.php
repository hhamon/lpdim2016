<?php
/**
 * Created by PhpStorm.
 * User: leosauvaget
 * Date: 17/02/2016
 * Time: 20:03
 */

namespace Application\Repository;


use Application\Repository\Model\BlogPost;
use Application\Repository\Model\BlogPostCollection;

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

        return BlogPost::parseToBlogPostObject($stmt->fetch(\PDO::FETCH_ASSOC));
    }

    public function getMostRecentPosts($limit)
    {
        $limit = (int)$limit;

        $query = <<<SQL
SELECT * FROM blog_post
WHERE published_at iS NULL or published_at <= NOW()
ORDER BY published_at DESC
LIMIT {$limit};
SQL;

        return BlogPostCollection::createBlogCollectionFromArray($this->fetchAll($query));
    }


    public function updateBlogPost(BlogPost $blogPost)
    {
        $this->dbh->beginTransaction();

        $sql = <<<SQL
UPDATE blog_post
set title = :title,
  contentMarkdown = :contentMarkdown,
  contentHTML = :contentHTML,
  published_at = :published_at
where id = :id
SQL;


        $blogPostKeys = $blogPost->getAllKeys();

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam('id', $blogPostKeys['id'], \PDO::PARAM_INT);
        $stmt->bindParam('title', $blogPostKeys['title'], \PDO::PARAM_STR);
        $stmt->bindParam('contentMarkdown', $blogPostKeys['contentMarkdown'], \PDO::PARAM_STR);
        $stmt->bindParam('contentHTML', $blogPostKeys['contentHTML'], \PDO::PARAM_STR);
        $stmt->bindParam('published_at', $blogPostKeys['published_at'], \PDO::PARAM_STR);

        $stmt->execute();

        try {
            return $this->dbh->commit();
        } catch (\PDOException $e) {
            $this->dbh->rollBack();
            throw new \RuntimeException(sprintf('Error pending sql request, no changing effective. See : %s', $e->getMessage()));
        }
    }


    public function createBlogPost(BlogPost $blogPost)
    {
        $this->dbh->beginTransaction();

        $sql = <<<SQL
INSERT INTO blog_post (`title`, `contentMarkdown`, `contentHTML`, published_at)
VALUES  (:title, :contentMarkdown, :contentHTML, :published_at)
SQL;

        $stmt = $this->dbh->prepare($sql);
        $blogPostKeys = $blogPost->getAllKeys();

        $stmt->bindParam('title', $blogPostKeys['title'], \PDO::PARAM_STR);
        $stmt->bindParam('contentMarkdown', $blogPostKeys['contentMarkdown'], \PDO::PARAM_STR);
        $stmt->bindParam('contentHTML', $blogPostKeys['contentHTML'], \PDO::PARAM_STR);
        $stmt->bindParam('published_at', $blogPostKeys['published_at'], \PDO::PARAM_STR);

        $stmt->execute();

        $blogPost->setId($this->dbh->lastInsertId());

        try {
            return $this->dbh->commit();
        } catch (\PDOException $e) {
            $this->dbh->rollBack();
            $blogPost->setId(null);
            throw new \RuntimeException(sprintf('Error pending sql request, no changing effective. See : %s', $e->getMessage()));
        }
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