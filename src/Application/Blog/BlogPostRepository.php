<?php

namespace Application\Blog;

class BlogPostRepository
{
    private $dbh;
    private $insertOnePostStmt;
    private $selectOnePostStmt;
    private $selectMostRecentPostsStmt;

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
            'htmlContent' => $post->getHtmlContent(),
            'publishedAt' => $post->getPublishedAt()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Lazy loads the \PDOStatement instance to insert a new blog post.
     *
     * @return \PDOStatement
     */
    private function insertOnePostStatement()
    {
        if ($this->insertOnePostStmt) {
            return $this->insertOnePostStmt;
        }

        $query = <<<SQL
INSERT INTO `blog_post`
(`title`, `content`, `html_content`, `published_at`)
VALUES
(:title, :content, :htmlContent, :publishedAt);
SQL;

        $this->insertOnePostStmt = $this->dbh->prepare($query);

        return $this->insertOnePostStmt;
    }

    /**
     * Lazy loads the \PDOStatement instance to select one blog post.
     *
     * @return \PDOStatement
     */
    private function selectOnePostStatement()
    {
        if ($this->selectOnePostStmt) {
            return $this->selectOnePostStmt;
        }

        $query = <<<SQL
SELECT * FROM blog_post
WHERE (published_at iS NULL OR published_at <= NOW()) AND id = :id;
SQL;

        $this->selectOnePostStmt = $this->dbh->prepare($query);

        return $this->selectOnePostStmt;
    }

    /**
     * Lazy loads the \PDOStatement instance to select most recent blog posts.
     *
     * @return \PDOStatement
     */
    private function selectMostRecentPostsStatement()
    {
        if ($this->selectMostRecentPostsStmt) {
            return $this->selectMostRecentPostsStmt;
        }

        $query = <<<SQL
SELECT * FROM blog_post
WHERE published_at iS NULL or published_at <= NOW()
ORDER BY published_at DESC
LIMIT :limit;
SQL;

        $this->selectMostRecentPostsStmt = $this->dbh->prepare($query);

        return $this->selectMostRecentPostsStmt;
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
            $stmt = $this->insertOnePostStatement();
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

    /**
     * Finds a BlogPost entity by its primary key.
     *
     * @param int $pk
     *
     * @return BlogPost
     */
    public function find($pk)
    {
        $stmt = $this->selectOnePostStatement();
        $stmt->bindValue('id', $pk, \PDO::PARAM_INT);
        $stmt->execute();

        if (!$record = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            throw new BlogPostNotFoundException(sprintf('Blog post #%u does not exist or is not published!', $pk));
        }

        return $this->hydrateObject($record);
    }

    public function getMostRecentPosts($limit)
    {
        $stmt = $this->selectMostRecentPostsStatement();
        $stmt->bindValue('limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $this->fetchAll($stmt);
    }

    private function fetchAll(\PDOStatement $stmt)
    {
        $entities = [];
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $record) {
            $entities[] = $this->hydrateObject($record);
        }

        return $entities;
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

    private function hydrateObject(array $record)
    {
        $post = BlogPost::fromArray($record);

        if (!empty($record['id'])) {
            $this->populateEntityPk($post, $record['id']);
        }

        return $post;
    }
}
