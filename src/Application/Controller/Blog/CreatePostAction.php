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
use Michelf\Markdown;

class CreatePostAction extends AbstractAction
{
    /**
     * Could show form to create a post or create the post
     * @param Request $request
     * @return \Framework\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $args = [];
        $args['html'] = new HtmlBuilder();
        $session = $this->getService('session');
        //check the method
        if($request->getMethod() == "POST"){
            $title = $request->getRequestParameter('title',false);
            $content = $request->getRequestParameter('content',false);
            $args['session'] = $session;
            //verify params
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
            //create the post and get his id
            $repository = $this->getService('repository.blog_post');
            $html = addslashes(Markdown::defaultTransform($content));
            if($id = $repository->create([
                'title' => $title,
                'content' => $html,
                'content_markdown' => $content
            ])){
                //redirect to his page
                return $this->redirect(
                    "/index.php/blog/article-{$id}.html"
                    ,301
                );
            }
        }
        //if there is no data posted we show the create view
        return $this->render('blog/create.twig',$args);
    }
}