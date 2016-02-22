<?php

namespace Application\Controller\Blog;

use Framework\AbstractAction;
use Framework\Http\Request;

class NewPostAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        $args['error'] = "";
        if($request->getMethod() == "POST"){
            $title = $request->getRequestParameter('title',false);
            $content = $request->getRequestParameter('content',false);
            if(!$title || !$content){
                $args['error'] = "All fields must be set";
                return $this->render('blog/new.twig',$args);
            }

            $repository = $this->getService('repository.blog_post');
            if($id = $repository->create([
                'title' => $title,
                'content' => $content
            ])){
                return $this->redirect(
                    "../article-{$id}.html"
                    ,301
                );
            }
        }
        return $this->render('blog/new.twig',$args);
    }
}
