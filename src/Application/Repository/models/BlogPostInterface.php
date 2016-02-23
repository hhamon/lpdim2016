<?php

namespace Application\Repository\Model;

interface BlogPostInterface
{
    public function getTitle();
    public function getContentHTML();
    public function getContentMarkdown();
    public function getPublishedAt();
    public function getAllKeys();
    public function getId();
    public function setId($id);
    public function setTitle($title);
    public function setContentMarkdown($contentMarkdown);
    public function setPublishedAt($published_at);

}