<?php
/**
 * Created by PhpStorm.
 * User: leosauvaget
 * Date: 30/01/2016
 * Time: 10:32
 */

namespace Application\Controller\Blog;


use Application\Repository\Model\BlogPost;
use Framework\AbstractAction;
use Framework\Http\Request;

class CreatePostAction extends AbstractAction
{

    private $blogPost;
    private $errors;
    private $title;
    private $contentMarkdown;
    private $published_at;





    public function __invoke(Request $request)
    {
        if($request->getMethod()=== 'POST'){
            return $this->saveNewBlogPost($request);
        }

        return $this->showBlogPostCreator();

    }

    //GET /blog/new
    private function showBlogPostCreator()
    {
        $renderParams = [
            'post' =>
                [
                    'action' => '/blog/new',
                    'title' => $this->title,
                    'contentMarkdown' => $this->contentMarkdown,
                    'published_at' => $this->published_at,
                ],
            'errors' => $this->errors
        ];
        return $this->render('blog/formBlogPost.twig', $renderParams);

    }


    //POST /blog/new
    private function saveNewBlogPost(Request $request)
    {

        if(!$postData = $this->postDataIsValid($request)){
            return $this->showBlogPostCreator();
        }

        $repository = $this->getService('repository.blog_post');

        $blogPost = BlogPost::parseToBlogPostObject($postData);

        if (!$query = $repository->createBlogPost($blogPost)) {
            throw new \RuntimeException(sprintf('error during article creation with values :  %s  - %s - %s',
                $blogPost->getTitle(),
                $blogPost->getContentMarkdown(),
                $blogPost->getPublishedAt()
            ));
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
        if (empty($this->title = $request->getRequestParameter('title'))) {
            $this->errors[] = 'The title is empty, please fill-in for create post.';
        }

        if (empty($this->contentMarkdown = $request->getRequestParameter('contentMarkdown'))) {
            $this->errors[] = 'The content is empty, please fill-in for create post.';
        }

        if (empty($this->published_at = $request->getRequestParameter('published_at'))) {
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


}