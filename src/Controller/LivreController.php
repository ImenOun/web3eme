<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\BookType;
use App\Repository\LivreRepository; // Assurez-vous que ceci est importé
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
class LivreController extends AbstractController
{
    #[Route('/listBook', name: 'app_livre')]
    public function index(): Response
    {
        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
        ]);
    }

    #[Route('/livres', name: 'app_list_book')] 
    public function list(LivreRepository $livreRepository,AuthorRepository $authorRep,Request $request): Response // Utilisez LivreRepository
    {
        //search
        $authorName=$request->query->get('authorName');
        if($authorName==''){$books = $livreRepository->findAll();}
        else{//recuperer author search author by authorName
            $author=$authorRep->findOneBy(['name'=>$authorName]);

            //recuperer liste by author name
            $books=$livreRepository->findByAuthor($author);
            if(count($books)==0){return new Response('No book found');}
        }
        // Récupérer tous les livres
        
         // Compter le nombre de livres dans la catégorie "Romance"
         $count = $livreRepository->countBooksByCategory('romance');

        return $this->render('livre/index.html.twig', ['books' => $books,'count' => $count,]);
    }

    #[Route('/addBook', name: 'app_book_add')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre(); // Crée une nouvelle instance de Livre
        $form = $this->createForm(BookType::class, $livre); // Crée le formulaire

        $form->handleRequest($request); // Gère la requête

        if ($form->isSubmitted() && $form->isValid()) {
            $livre->setPublished(true); // Assurez-vous que l'attribut "published" est à true

            $entityManager->persist($livre); // Prépare l'entité à être enregistrée
            $entityManager->flush(); // Enregistre dans la base de données

            return $this->redirectToRoute('app_list_book'); // Redirige vers la liste des livres après l'ajout
        }
        dump($form); 
        return $this->render('livre/addBook.html.twig', [
            'form' => $form->createView(), // Passe le formulaire à la vue
        ]);
    }
    #[Route('/editBook/{id}', name: 'app_book_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupérer le livre par son ID
        $livre = $entityManager->getRepository(Livre::class)->find($id);
        
        if (!$livre) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }
    
        $form = $this->createForm(BookType::class, $livre);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Mettre à jour le livre dans la base de données
    
            return $this->redirectToRoute('app_list_book'); // Redirige vers la liste des livres
        }
    
        return $this->render('livre/editBook.html.twig', [
            'form' => $form->createView(),
            'livre' => $livre,
        ]);
    }
    
    #[Route('/deleteBook/{id}', name: 'app_book_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupérer le livre par son ID
        $livre = $entityManager->getRepository(Livre::class)->find($id);
        
        if (!$livre) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        $entityManager->remove($livre); // Supprime le livre
        $entityManager->flush(); // Enregistre les changements

        $this->addFlash('success', 'Book deleted successfully.');

        return $this->redirectToRoute('app_list_book'); // Redirige vers la liste des livres
    }
    #[Route('/book/{id}', name: 'app_book_show')]
        public function show(EntityManagerInterface $entityManager, int $id): Response
        {
            // Récupérer le livre par son ID
            $livre = $entityManager->getRepository(Livre::class)->find($id);
            
            if (!$livre) {
                throw $this->createNotFoundException('No book found for id ' . $id);
            }

            return $this->render('livre/show.html.twig', [
                'livre' => $livre,
            ]);
        }
        public function countRomanceBooks(LivreRepository $livreRepository)
        {
            $count = $livreRepository->countBooksByCategory('romance');
        
            return $this->render('livre/index.html.twig', [
                'count' => $count,
            ]);
        }
        // src/Controller/BookController.php
public function listBooksBetweenDates(LivreRepository $livreRepository)
{
    $startDate = new \DateTime('2014-01-01');
    $endDate = new \DateTime('2018-12-31');
    $books = $livreRepository->findBooksBetweenDates($startDate, $endDate);

    return $this->render('book/list_between_dates.html.twig', [
        'books' => $books,
    ]);
}

}
