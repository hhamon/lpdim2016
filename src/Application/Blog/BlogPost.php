<?php

namespace Application\Blog;

class BlogPost
{
    private $id;
    private $title;
    private $content;
    private $htmlContent;
    private $publishedAt;

    /**
     * Constructor.
     *
     * @param string $title
     * @param string $content
     * @param string $htmlContent
     * @param string $publishedAt
     */
    public function __construct($title, $content, $htmlContent = null, $publishedAt = 'now')
    {
        $this->title = $title;
        $this->changeContent($content, $htmlContent);
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

        if (empty($data['html_content'])) {
            throw new \LogicException('The html_content property is missing.');
        }

        $publishedAt = 'now';
        if (!empty($data['published_at'])) {
            $publishedAt = $data['published_at'];
        }

        return new self($data['title'], $data['content'], $data['html_content'], $publishedAt);
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

    public function getHtmlContent()
    {
        return $this->htmlContent;
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

    public function changeContent($newContent, $newHtmlContent = null)
    {
        $this->content = $newContent;
        $this->htmlContent = $newHtmlContent;
    }

    public function changePublicationDate($newPublicationDate)
    {
        $this->publishedAt = new \DateTime($newPublicationDate);
    }
}
