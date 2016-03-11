<?php

namespace Application\Controller\Blog;

use Application\Blog\BlogPost;
use Application\Blog\BlogPostValidator;
use Application\Blog\CreateBlogPostForm;
use Framework\AbstractAction;
use Framework\Http\Request;

class CreatePostAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        $form = new CreateBlogPostForm(new BlogPostValidator());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = BlogPost::fromArray($form->getData());
            $this->getService('repository.blog_post')->save($post);

            return $this->redirectToRoute('blog_post', ['id' => $post->getId()]);
        }

        return $this->render('blog/create.twig', [ 'form' => $form->createView() ]);
    }
}
