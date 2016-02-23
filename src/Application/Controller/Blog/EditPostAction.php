<?php

namespace Application\Controller\Blog;

use Framework\AbstractAction;
use Framework\Http\Request;
use Michelf\Markdown;

class EditPostAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        $id = $request->getAttribute('id');
        $repository = $this->getService('repository.blog_post');
        $blog_post = $repository->find($id);
        $args['error'] = "";
        if($request->getMethod() == "POST"){
            $args['blog_post'] = $blog_post;
            $title = $request->getRequestParameter('title',false);
            $content = $request->getRequestParameter('content',false);


            if(!$title || !$content){
                $args['error'] = "All fields must be set";
                return $this->render('blog/new.twig',$args);
            }

            $html = addslashes(Markdown::defaultTransform($content));
            $repository = $this->getService('repository.blog_post');
            if($repository->edit($id,[
                'title' => $title,
                'content' => $html,
                'markdownContent' => $content
            ])){
                return $this->redirect(
                    "../article-{$id}.html"
                    ,301
                );
            }
        }

        return $this->render(
            'blog/edit.twig',
            compact('blog_post','error')
        );
    }
}
