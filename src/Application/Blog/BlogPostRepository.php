<?php

namespace Application\Blog;

class BlogPostRepository
{
    private $dbh;
    private $insertStmt;

    /**
     * Constructor.
     *
     * @param \PDO $dbh
     */
    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * Extracts the blog post state as an associative array.
     *
     * @param BlogPost $post
     *
     * @return array
     */
    private static function asArray(BlogPost $post)
    {
        return [
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'publishedAt' => $post->getPublishedAt()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Lazy loads the \PDOStatement instance to insert a new blog post.
     *
     * @return \PDOStatement
     */
    private function insertStatement()
    {
        if ($this->insertStmt) {
            return $this->insertStmt;
        }

        return $this
            ->dbh
            ->prepare('INSERT INTO `blog_post` (`title`, `content`, `published_at`) VALUES (:title, :content, :publishedAt);')
        ;
    }

    /**
     * Saves a blog post to the database.
     
     * @param BlogPost $post
     * @throws \Exception
     */
    public function save(BlogPost $post)
    {
        try {
            $this->dbh->beginTransaction();
            $stmt = $this->insertStatement();
            $stmt->execute(static::asArray($post));
            $id = (int) $this->dbh->lastInsertId();
            $this->dbh->commit();
            $this->populateEntityPk($post, $id);
        } catch (\PDOException $e) {
            $this->dbh->rollBack();
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
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

    private function fetchAll($sql)
    {
        return $this->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function query($sql)
    {
        return $this->dbh->query($sql);
    }

    /**
     * Sets the primary key of the blog post entity using a PHP Reflection trick.
     *
     * @param BlogPost $post The blog post to modify
     * @param int      $pk   The blog post primary key
     */
    private function populateEntityPk(BlogPost $post, $pk)
    {
        $ro = new \ReflectionObject($post);
        $rp = $ro->getProperty('id');
        $rp->setAccessible(true);
        $rp->setValue($post, $pk);
    }
}
