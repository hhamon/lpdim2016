<?php
/**
 * Created by PhpStorm.
 * User: leosauvaget
 * Date: 30/01/2016
 * Time: 10:32
 */

namespace Application\Controller\Blog;


use Framework\AbstractAction;
use Framework\Http\Request;

class CreatePostAction extends AbstractAction
{

    private $title;
    private $content;
    private $published_at;
    private $errors;




    public function __invoke(Request $request)
    {
        if($request->getMethod()=== 'POST'){
            return $this->saveNewBlogPost($request);
        }

        return $this->showBlogPostCreator($request);

    }

    //GET /blog/new
    private function showBlogPostCreator(Request $request)
    {
        $renderParams = [
            'post' =>
                [
                    'action' => '/blog/new',
                    'title' => $this->title,
                    'content' => $this->content,
                    'published_at' => $this->published_at,
                ],
            'errors' => $this->errors
        ];
        return $this->render('blog/newBlogPost.twig', $renderParams);

    }


    //POST /blog/new
    private function saveNewBlogPost(Request $request)
    {

        if(!$this->postDataIsValid($request)){
            return $this->showBlogPostCreator($request);
        }

        $repository = $this->getService('repository.blog_post');

        if (!$query = $repository->createBlogPosts($this->title, $this->content, $this->published_at)) {
            throw new \RuntimeException(sprintf('error during article creation with values :  %s  - %s - %s',
                $this->title,
                $this->content,
                $this->published_at)
            );
        };

        $dateNow = new \DateTime();
        $articleDate = new \DateTime($this->published_at);

        if($dateNow<$articleDate){
            return $this->redirect('/blog');
        }

        return $this->redirect(sprintf('/blog/article-%d', (int) $repository->getLastPostId()));

    }



    private function postDataIsValid(Request $request)
    {
        if (empty($this->title = $request->getRequestParameter('title'))) {
            $this->errors[] = 'The title is empty, please fill-in for create post.';
        }

        if (empty($this->content = $request->getRequestParameter('content'))) {
            $this->errors[] = 'The content is empty, please fill-in for create post.';
        }

        if (empty($this->published_at = $request->getRequestParameter('published_at'))) {
            $this->errors[] = 'The date is empty, please fill-in for create post.';
        }

        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }


}