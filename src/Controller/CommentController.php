<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Attributes\DefaultEntity;
use Core\Attributes\Route;
use Core\Controller\Controller;
use Core\Http\Response;


#[DefaultEntity(entityName: Comment::class)]
class CommentController extends Controller
{


    #[Route(uri: "/comment/new", routeName: "comment_new", methods: ["POST"])]
    public function save():Response
    {
        $commentForm = new CommentType();
        if($commentForm->isSubmitted()) {



            $comment = new Comment();
            $comment->setContent($commentForm->getValue('content'));
            $comment->setPostId($commentForm->getValue('postId'));

            $this->getRepository()->save($comment);

            return $this->redirectToRoute('post', ["id" => $comment->getPostId()]);
        }
      return $this->redirectToRoute('posts');
    }

}