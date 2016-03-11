<?php

namespace Application\Controller\Blog;

use Application\Blog\BlogPostNotFoundException;
use Framework\AbstractAction;
use Framework\Http\HttpNotFoundException;
use Framework\Http\Request;

class GetPostAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        try {
            $repository = $this->getService('repository.blog_post');
            $post = $repository->find($request->getAttribute('id'));
        } catch (BlogPostNotFoundException $e) {
            throw new HttpNotFoundException(sprintf('No blog post found for id #%u.', $request->getAttribute('id')), $e->getCode(), $e);
        }

        return $this->render('blog/show.twig', ['post' => $post]);
    }
}
