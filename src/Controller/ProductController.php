<?php

namespace App\Controller;

//use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/produit/{slug}', name: 'app_product')]
    public function index($slug, ProductRepository $productRepository): Response

    // Another method of symfony that do automapping to the entity Product without using ProductRepository
    // public function index($slug, #[MapEntity(slug: 'slug')] Product $product): Response
    {

        $product = $productRepository->findOneBySlug($slug);

        if(!$product){
            return $this->redirectToRoute('app_home');
        }
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
