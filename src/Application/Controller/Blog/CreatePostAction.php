<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 17/02/2016
 * Time: 21:45
 */

namespace Application\Controller\Blog;


use Application\Html\HtmlBuilder;
use Framework\AbstractAction;
use Framework\Http\Request;

class CreatePostAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        $args = [];
        $args['html'] = new HtmlBuilder();
        $session = $this->getService('session_blog_post');
        //check the method
        if($request->getMethod() == "POST"){
            $title = $request->getRequestParameter('title',false);
            $content = $request->getRequestParameter('content',false);
            $args['session'] = $session;
            if(!$title && !$content){
                $session->store('error','Aucun champ n\'a été rempli');
                return $this->render('blog/create.twig',$args);
            }
            if(!$content && $title){
                $session->store('error','Le contenu du blog post ne doit pas être vide');
                $args['title'] = $title;
                return $this->render('blog/create.twig',$args);
            }
            if($content && !$title){
                $session->store('error','Le titre du blog post ne doit pas être vide');
                $args['content'] = $content;
                return $this->render('blog/create.twig',$args);
            }
            $repository = $this->getService('repository.blog_post');
            $html = addslashes(Markdown::defaultTransform($content));
            if($id = $repository->create([
                'title' => $title,
                'content' => $html,
                'content_markdown' => $content
            ])){
                return $this->redirect(
                    "/index.php/blog/article-{$id}.html"
                    ,301
                );
            }
        }
        return $this->render('blog/create.twig',$args);
    }
}