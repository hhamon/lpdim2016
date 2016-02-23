<?php

namespace Application\Controller\Blog;

use Framework\AbstractAction;
use Framework\Http\RequestInterface;

class ListPostsAction extends AbstractAction
{
    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function __invoke(RequestInterface $request)
    {
        $repository = $this->getService('repository.blog_post');

        return $this->render('blog/index.twig', [
            'posts' => $repository->getMostRecentPosts(5),
        ]);
    }
}
