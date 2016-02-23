<?php
/**
 * Created by PhpStorm.
 * User: leosauvaget
 * Date: 14/02/2016
 * Time: 20:13
 */

namespace Application\Controller\Blog;


use Application\Repository\Model\BlogPostInterface;
use Framework\AbstractAction;
use Framework\Http\HttpNotFoundException;
use Framework\Http\Request;

class UpdatePostAction extends AbstractAction
{
    private $errors;
    private $repository;

    public function __invoke(Request $request)
    {

        $id = $this->testIdValidity($request);

        $this->repository = $this->getService('repository.blog_post');
        $blogPost = $this->repository->find($id);

        if (!$blogPost) {
            throw new HttpNotFoundException(sprintf('No blog post found for id #%u.', $id));
        }



        if($request->getMethod()=== 'POST'){
            return $this->updateBlogPost($request, $blogPost);
        }

        return $this->showBlogPostUpdater($blogPost);
    }


    //GET /blog/new
    private function showBlogPostUpdater(BlogPostInterface $blogPost)
    {
        $renderParams = [
            'post' =>
                [
                    'action' => '/blog/article-'.$blogPost->getId().'/update',
                    'title' => $blogPost->getTitle(),
                    'content' => $blogPost->getContentMarkdown(),
                    'published_at' => $blogPost->getPublishedAt(),
                ],
            'errors' => $this->errors
        ];
        return $this->render('blog/formBlogPost.twig', $renderParams);

    }


    //POST /blog/new
    private function updateBlogPost(Request $request, BlogPostInterface $blogPost)
    {

        $postData = $this->postDataIsValid($request);

        if(!$postData){
            return $this->showBlogPostUpdater($blogPost);
        }

        $blogPost->setTitle($postData['title']);
        $blogPost->setContentMarkdown($postData['contentMarkdown']);
        $blogPost->setPublishedAt($postData['published_at']);



        if (!$query = $this->repository->updateBlogPost($blogPost)) {
            throw new \RuntimeException(sprintf('error during article creation with values :  %s  - %s - %s',
                    $blogPost->getTitle(),
                    $blogPost->getContentMarkdown(),
                    $blogPost->getPublishedAt())
            );
        };

        $dateNow = new \DateTime();
        $articleDate = new \DateTime($blogPost->getPublishedAt());

        if($dateNow<$articleDate){
            return $this->redirect('/blog');
        }

        return $this->redirect(sprintf('/blog/article-%d', (int) $blogPost->getId()));

    }



    private function postDataIsValid(Request $request)
    {
        if (empty($request->getRequestParameter('title'))) {
            $this->errors[] = 'The title is empty, please fill-in for create post.';
        }

        if (empty($request->getRequestParameter('contentMarkdown'))) {
            $this->errors[] = 'The content is empty, please fill-in for create post.';
        }

        if (empty($request->getRequestParameter('published_at'))) {
            $this->errors[] = 'The date is empty, please fill-in for create post.';
        }

        if (count($this->errors) > 0) {
            return false;
        }
        return [
            'title' => $request->getRequestParameter('title'),
            'contentMarkdown' => $request->getRequestParameter('contentMarkdown'),
            'published_at' => $request->getRequestParameter('published_at')
        ];
    }

    /**
     * @param Request $request
     * @return null
     * @throws \HttpRuntimeException
     */
    private function testIdValidity(Request $request)
    {
        $id = $request->getAttribute('id');

        if (!is_numeric($id)) {
            throw new \HttpRuntimeException(
                sprintf('Bad request, id article should be a number and not %s', $request->getAttribute('id')
                ));
        }
        return $id;
    }
}