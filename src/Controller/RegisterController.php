<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entitytManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()){
            //dd($form -> getData()); for debbuging 
            
            $entitytManager -> persist($user);
            $entitytManager -> flush();
            $this->addFlash(
                'success',
                'Votre compte est correctement crée, veuillez vous connecter.'
            );

            $mail = new Mail();
            $vars = [
                'firstname' => $user->getFirstName(),
            ];
            $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Bienvenue sur La Boutique Française', "welcome.html", $vars);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'registerForm'=> $form->createView()
        ]);
    }
}
