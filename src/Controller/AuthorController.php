<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    private $authors;
    public function __construct(){
        $this->authors=[ ['id'=>1, 'name'=>'Taha Hussain','nbrBooks'=>300,'picture'=>'images/th.jpeg','email'=>'Taha.Hussain@gmail.com'],
        ['id'=>2, 'name'=>'Victor Hugo','nbrBooks'=>200,'picture'=>'images/vh.jpeg','email'=>'Victor.Hugo@gmail.com'],
        ['id' => 3,'name'=>'William shakespear', 'nbrBooks' => 400, 'picture' => 'images/ws.jpeg','email'=>'William.Shakespear@gmail.com'],
    
           ];
}
    #[Route('/library', name: 'app_library',methods:["GET"])]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/{name}',name:'app_author',methods:["GET"],defaults:["name"=>"taha hussain"])]
    public function showAuthor($name)
    {
    return $this->render('author/show.html.twig', array('name'=>$name));
    }
    #[Route('/list',name:'app_list',methods:["GET"])]
    public function listAuthor()
    {
        return $this->render('author/list.html.twig',['authors'=>$this->authors]);
    }


    #[Route('/author/details/{id}',name:'author_details',methods:["GET"])]
            public function authorDetails(int $id): Response
        {
            // Rechercher l'auteur par son ID dans la liste des auteurs
            $author = array_filter($this->authors, function ($a) use ($id) {
                return $a['id'] === $id; 
            });

            // Si l'auteur existe, on le sélectionne, sinon on retourne une erreur 404
            $author = array_values($author);
            if (empty($author)) {
                throw $this->createNotFoundException('Author not found');
            }

            return $this->render('author/show.html.twig', [
                'author' => $author[0], 
            ]);
        }
       
       

    
}
