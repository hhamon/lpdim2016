<?php
/**
 * Created by PhpStorm.
 * User: leosauvaget
 * Date: 17/02/2016
 * Time: 20:06
 */

namespace Application\Repository\Model;


class BlogPostCollection implements \Iterator, \Countable
{
    private $blogPosts;

    /**
     * BlogPostCollection constructor.
     * @param $blogPosts
     */
    public function __construct($blogPosts)
    {
        $this->blogPosts = $blogPosts;
    }

    public function add(BlogPostInterface $blogPost){
        $this->blogPosts[] = $blogPost;
    }

    public static function createBlogCollectionFromArray($blogPosts){
        if(!is_array($blogPosts) || count($blogPosts)==0){
            throw new \InvalidArgumentException("The array of blog blogPost is empty or not be an array");
        }
        $tmpBlogPosts = [];
        foreach($blogPosts as $blogPost){
            $tmpBlogPosts[] = BlogPost::parseToBlogPostObject($blogPost);
        }
        return new self($tmpBlogPosts);
    }


    public function current()
    {
        return current($this->blogPosts);
    }

    public function next()
    {
        next($this->blogPosts);
    }

    public function key()
    {
       return key($this->blogPosts);
    }

    public function valid()
    {
        return $this->current() instanceOf BlogPostInterface;
    }

    public function rewind()
    {
        reset($this->blogPosts);
    }


    public function count()
    {
        return count($this->blogPosts);
    }
}