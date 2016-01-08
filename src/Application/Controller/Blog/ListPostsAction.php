<?php

namespace Application\Controller\Blog;

use Framework\AbstractAction;
use Framework\Http\RequestInterface;

class ListPostsAction extends AbstractAction
{
    public function __invoke(RequestInterface $request)
    {
        /** @var \PDO $database */
        $database = $this->getService('database');

        $posts = $database
            ->query('SELECT * FROM blog_post WHERE published_at <= NOW() ORDER BY published_at DESC LIMIT 10;')
            ->fetchAll(\PDO::FETCH_ASSOC)
        ;

        return $this->render('blog/index.twig', [
            'posts' => $posts,
        ]);
    }
}
