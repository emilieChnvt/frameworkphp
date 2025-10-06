<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Attributes\DefaultEntity;
use Core\Attributes\Route;
use Core\Controller\Controller;
use Core\Http\Response;



#[DefaultEntity(entityName: Post::class)]
class PostController extends Controller
{

    #[Route(uri:'/posts', routeName: 'posts')]
    public function index():Response
    {

        return $this->render('post/index', [
            "posts"=> $this->getRepository()->findAll()
        ]);
    }

    #[Route(uri: "/post/show", routeName: "post")]
    public function show():Response
    {
        $id = $this->getRequest()->get(["id"=>"number"]);

        if(!$id){return $this->redirectToRoute("posts");}

        $post = $this->getRepository()->find($id);

        if(!$post){return $this->redirectToRoute("posts");}



        return $this->render('post/show', ["post"=>$post]);
    }


    #[Route(uri: "/post/new", routeName: "newPost")]
    public function create():Response
    {

        $postForm = new PostType();
        if($postForm->isSubmitted()){

            $post = new Post();
            $post->setTitle($postForm->getValue("title"));
            $post->setContent($postForm->getValue("content"));
            $id = $this->getRepository()->save($post);
            return $this->redirectToRoute("post", ["id"=>$id]);


        }




        return $this->render('post/create', []);
    }


    #[Route(uri: "/post/update", routeName: "editPost")]
public function update():Response
{

        $id = $this->getRequest()->get(["id"=>"number"]);

        if(!$id){return $this->redirectToRoute("posts");}

        $post = $this->getRepository()->find($id);

        if(!$post){return $this->redirectToRoute("posts");}


        $formPost = new PostType();
        if($formPost->isSubmitted())
        {


            $post->setTitle($formPost->getValue("title"));
            $post->setContent($formPost->getValue("content"));
            $post = $this->getRepository()->update($post);

            return $this->redirectToRoute("post", ["id"=>$post->getId()]);
        }



        return $this->render('post/update', ["post"=>$post]);


}


#[Route(uri: "/post/delete", routeName: "deletePost")]
public function delete():Response
{
    $id = $this->getRequest()->get(["id"=>"number"]);
    if(!$id){return $this->redirectToRoute("posts");}
    $post = $this->getRepository()->find($id);
    if(!$post){return $this->redirectToRoute("posts");}

    $this->getRepository()->delete($post);
    return $this->redirectToRoute("posts");

}

}