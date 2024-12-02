<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

Class Cart
{

    public function  __construct(private RequestStack $requestStack)
    {

    }
    public function add($product)
    {

        $cart = $this->getCart();


        if (isset($cart[$product->getId()]))
        {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1,
            ];
        }else{
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1,
            ];
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function decrease($id){
        $cart = $this->getCart();
        if($cart[$id]['qty'] > 1 ){
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        }else{
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function fullQuantity(){

        $cart = $this->getCart();
        $quantity = 0;

        if(!isset($cart)){
            return $quantity;
        }

        foreach ($cart as $product){
            $quantity = $quantity + $product['qty'];
        }

        return $quantity;
    }

    public function getTotalWt(){

        $cart = $this->getCart();
        $price = 0;

        if(!isset($cart)){
            return $price;
        }

        foreach ($cart as $product){
            $price = $price + ($product['object']->getPriceWt() * $product['qty']);
        }

        return $price;
    }

    public function remove(){
        return $this->requestStack->getSession()->remove('cart');
    }

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }


}