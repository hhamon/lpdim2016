<?php

namespace Application\Blog;

class BlogPost
{
    private $id;
    private $title;
    private $content;
    private $publishedAt;

    /**
     * Constructor.
     *
     * @param string $title
     * @param string $content
     * @param string $publishedAt
     */
    public function __construct($title, $content, $publishedAt = 'now')
    {
        $this->title = $title;
        $this->content = $content;
        $this->publishedAt = new \DateTime($publishedAt);
    }

    /**
     * Constructs a new BlogPost entity from an array.
     *
     * @param array $data
     *
     * @return BlogPost
     *
     * @throws \LogicException
     */
    public static function fromArray(array $data)
    {
        if (empty($data['title'])) {
            throw new \LogicException('The title property is missing.');
        }
    
        if (empty($data['content'])) {
            throw new \LogicException('The content property is missing.');
        }

        $publishedAt = 'now';
        if (!empty($data['published_at'])) {
            $publishedAt = $data['published_at'];
        }
        return new self($data['title'], $data['content'], $publishedAt);
    }

    public function getId()
    {
        return (int) $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    public function changeTitle($newTitle)
    {
        $this->title = $newTitle;
    }

    public function changeContent($newContent)
    {
        $this->content = $newContent;
    }

    public function changePublicationDate($newPublicationDate)
    {
        $this->publishedAt = new \DateTime($newPublicationDate);
    }
}
