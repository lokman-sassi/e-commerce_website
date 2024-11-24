<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Doctrine\Tests\Models\Taxi\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id,Cart $cart, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneById($id);
        $cart->add($product);

        $this->addFlash(
            'success',
            'Produit ajouté à votre panier !'
        );
        return $this->redirectToRoute('app_product', [
            'slug' => $product->getSlug(),
        ]);
    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {

        $cart->remove();

        return $this->redirectToRoute('app_home');
    }
}
