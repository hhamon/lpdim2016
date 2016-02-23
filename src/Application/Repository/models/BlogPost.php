<?php

namespace Application\Repository\Model;
use Michelf\Markdown;


class BlogPost implements BlogPostInterface
{
    private $id;
    private $title;
    private $contentHTML;
    private $contentMarkdown;
    private $published_at;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = htmlspecialchars($id);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContentHTML()
    {
        return $this->contentHTML;
    }

    public function getContentMarkdown()
    {
        return $this->contentMarkdown;
    }

    public function getPublishedAt()
    {
        return $this->published_at;
    }

    public function setTitle($title)
    {
        $this->title = htmlspecialchars($title);
    }

    public function getAllKeys(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'contentHTML' => $this->contentHTML,
            'contentMarkdown' => $this->contentMarkdown,
            'published_at' => $this->published_at,
        ];
    }


    public function setContentMarkdown($contentMarkdown)
    {
        $this->contentMarkdown = htmlspecialchars($contentMarkdown);
        $this->contentHTML = $this->convertMarkdownToHTML($this->contentMarkdown);
    }

    public function setPublishedAt($published_at)
    {
        $this->published_at = htmlspecialchars($published_at);
    }

    public function __construct($title, $contentMarkdown, $published_at, $id=null, $contentHTML=null)
    {
        $this->id = htmlspecialchars($id);
        $this->title = htmlspecialchars($title);
        $this->contentMarkdown = htmlspecialchars($contentMarkdown);
        $this->contentHTML = htmlspecialchars($contentHTML) ?: $this->convertMarkdownToHTML($contentMarkdown);
        $this->published_at = htmlspecialchars($published_at ?: date("Y-m-d H:i:s", strtotime($published_at)));
    }

    private function convertMarkdownToHTML($markdown){
        return Markdown::defaultTransform($markdown);
    }



    public static function parseToBlogPostObject($postArray){
        if(empty($postArray) || !is_array($postArray)){
            throw new \InvalidArgumentException(sprintf('Impossible to parse into BlogPost Object'));
        }
        if(!isset($postArray['title'])){
            throw new \InvalidArgumentException(sprintf('The title is missing to create blogPost Object : %s',implode(", ",$postArray)));
        }
        if( !isset($postArray['contentMarkdown'])){
            throw new \InvalidArgumentException(sprintf('The contentMarkdown is missing to create blogPost Object : %s',implode(", ",$postArray)));
        }

        if( !isset($postArray['published_at'])){
            throw new \InvalidArgumentException(sprintf('The published_at is missing to create blogPost Object : %s',implode(", ",$postArray)));
        }

        $id = isset($postArray['id']) ? $postArray['id'] : null;


        return new self($postArray['title'], $postArray['contentMarkdown'], $postArray['published_at'], $id);
    }









}