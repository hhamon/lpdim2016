<?php

namespace Application\Controller\Blog;

use Framework\AbstractAction;
use Framework\Http\HttpNotFoundException;
use Framework\Http\Request;

class GetPostAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        $repository = $this->getService('repository.blog_post');

        $id = $request->getAttribute('id');

        if(!is_numeric($id))
        {
            throw new \HttpRuntimeException(
                sprintf('Bad request, id article should be a number and not %s', $request->getAttribute('id')
                ));
        }

        if (!$post = $repository->find($id)) {
            throw new HttpNotFoundException(sprintf('No blog post found for id #%u.', $id));
        }
        //print_r($post);

        return $this->render('blog/show.twig', [ 'post' => $post, 'linkPosts' => '/blog' ]);
    }
}
