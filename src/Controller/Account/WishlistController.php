<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishlistController extends AbstractController
{
    #[Route('/compte/liste-de-souhait', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wishlist/index.html.twig');
    }

    #[Route('/compte/liste-de-souhait/add/{id}', name: 'app_account_wishlist_add')]
    public function add(ProductRepository $productRepository, $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = $productRepository->findOneById($id);

        if($product){
            $this->getUser()->addWishlist($product);
            $entityManager->flush();
        }
        $this->addFlash(
            'success',
            'Produit correctement ajouté à votre liste de souhait !'
        );
        return $this->redirect($request->headers->get('referer'));

    }

    #[Route('/compte/liste-de-souhait/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove(ProductRepository $productRepository, $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = $productRepository->findOneById($id);

        if($product){
            $this->getUser()->removeWishlist($product);
            $this->addFlash('success', 'Produit correctement supprimé de votre liste de souhait ');
            $entityManager->flush();
        }else{
            $this->addFlash('success', 'Produit Introuvable ');
        }
        return $this->redirect($request->headers->get('referer'));
    }
}
