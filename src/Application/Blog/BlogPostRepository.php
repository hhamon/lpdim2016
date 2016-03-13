<?php

namespace Application\Blog;

use Framework\Persistence\AbstractRepository;

class BlogPostRepository extends AbstractRepository
{
    private $insertOnePostStmt;
    private $selectOnePostStmt;
    private $selectMostRecentPostsStmt;

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

        $this->insertOnePostStmt = $this->prepare($query);

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

        $this->selectOnePostStmt = $this->prepare($query);

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

        $this->selectMostRecentPostsStmt = $this->prepare($query);

        return $this->selectMostRecentPostsStmt;
    }

    /**
     * Saves a blog post to the database.
     *
     * @param BlogPost $post
     * @throws \Exception
     */
    public function save(BlogPost $post)
    {
        $id = $this->executeTransaction(function () use ($post) {
            $stmt = $this->insertOnePostStatement();
            $stmt->execute(static::asArray($post));

            return (int) $this->lastInsertId();
        });

        static::populateEntityPk($post, $id);
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

    private function hydrateObject(array $record)
    {
        $post = BlogPost::fromArray($record);

        if (!empty($record['id'])) {
            static::populateEntityPk($post, $record['id']);
        }

        return $post;
    }
}
