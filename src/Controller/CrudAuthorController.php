<?php

namespace App\Controller;
use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request; 
use App\Repository\AuthorRepository;
#[Route('/crud')]
class CrudAuthorController extends AbstractController
{
    #[Route('/list', name: 'app_list_author')]
    public function list(AuthorRepository $authorRepository): Response
    {
        $author =$authorRepository->findAll();
        return $this->render('crud_author/list.html.twig',['authors'=>$author]);
    }
    #[Route('/search/{id}', name: 'app_search_author',methods: ['GET'])]
    public function search(AuthorRepository $authorRepository,$id): Response
    {
       
        $author =$authorRepository->find($id);
        var_dump($author);
        die();
        return $this->render('crud_author/list.html.twig',['authors'=>$author]);
    }
    #[Route('/addAuthor',name:'add_author')]
        public function add_author(EntityManagerInterface $entityManager)
        {
            //creation d un nouvel auteur avec des données statiques
            $author=new Author();
            $author->setName("victor higo");
            $author->setEmail("Victor.higo@gmail.com");
            //persistance de l auteur dans la base de données
            $entityManager->persist($author);
            $entityManager->flush();
            //return $this->render('crud_author/list.html.twig',['authors'=>$author]);
            return $this->redirectToRoute('app_list_author');
        }


        #[Route('/add-author', name: 'AddAuthor')]
        public function addAuthor(Request $request, EntityManagerInterface $entityManager): Response
        {
            $author = new Author();
            $form = $this->createForm(AuthorType::class, $author);
            
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($author);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_list_author');
            }
    
            return $this->render('crud_author/ajouFormu.html.twig', [
                'form' => $form->createView(),
            ]);
        }





        #[Route('/delete/{id}',name:'app_delete_author')]
        public function deleteAuthor(ManagerRegistry $doctrine,Author $author):Response
        {
            if($author)
            {
                $em=$doctrine->getManager();
                $em->remove($author);
                $em->flush();

            }
            return $this->redirectToRoute('app_list_author');
        }
        #[Route('/update/{id}',name:'app_update_author')]
        public function updateAuthor(ManagerRegistry $doctrine,Author $author):Response
        {
            if($author)
            {
                $em=$doctrine->getManager();
                $author->setName("imeeen");
                $em->flush();

            }
            return $this->redirectToRoute('app_list_author');
        }

        #[Route('/edit/{id}', name: 'app_edit_author')]
        public function editAuthor(Request $request, Author $author, EntityManagerInterface $entityManager): Response
        {
            // Créer le formulaire de modification
            $form = $this->createForm(AuthorType::class, $author);

            // Gérer la soumission du formulaire
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush(); // Enregistrer les modifications

                return $this->redirectToRoute('app_list_author'); // Rediriger vers la liste des auteurs
            }

            // Afficher le formulaire de modification
            return $this->render('crud_author/editForm.html.twig', [
                'form' => $form->createView(),
                'author' => $author,
            ]);
        }

}
