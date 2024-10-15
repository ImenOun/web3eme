<?php

namespace App\Controller;
use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\LivreRepository;
#[Route('/crudLivre')]
class CrudLivreController extends AbstractController
{
    #[Route('/list', name: 'app_list_livre')]
    public function list(AuthorRepository $authorRepository): Response
    {
        $author =$authorRepository->findAll();
        return $this->render('crud_livre/list.html.twig',['authors'=>$author]);
    }
    #[Route('/search/{id}', name: 'app_search_livre',methods: ['GET'])]
    public function search(AuthorRepository $authorRepository,$id): Response
    {
       
        $author =$authorRepository->find($id);
        var_dump($author);
        die();
        return $this->render('crud_livre/list.html.twig',['authors'=>$author]);
    }
    #[Route('/addLivre',name:'add_livre')]
        public function add_livre(EntityManagerInterface $entityManager)
        {
            //creation d un nouvel auteur avec des données statiques
            $liv=new Livre();
            $liv->setTitle("kteeb");
            //$author->setEmail("Victor.higo@gmail.com");
            //persistance de l auteur dans la base de données
            $entityManager->persist($liv);
            $entityManager->flush();
            //return $this->render('crud_author/list.html.twig',['authors'=>$author]);
            return $this->redirectToRoute('app_list_livre');
        }
        #[Route('/delete/{id}',name:'app_delete_livre')]
        public function deleteLivre(ManagerRegistry $doctrine,Livre $liv):Response
        {
            if($liv)
            {
                $em=$doctrine->getManager();
                $em->remove($liv);
                $em->flush();

            }
            return $this->redirectToRoute('app_list_livre');
        }
        #[Route('/update/{id}',name:'app_update_livre')]
        public function updateLivre(ManagerRegistry $doctrine,Livre $liv):Response
        {
            if($liv)
            {
                $em=$doctrine->getManager();
                $liv->setTitle("imeeen");
                $em->flush();

            }
            return $this->redirectToRoute('app_list_livre');
        }
}
