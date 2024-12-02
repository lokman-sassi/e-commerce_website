<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    #[Route('/mot-de-passe-oublié', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {

        $form = $this->createForm(ForgotPasswordFormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneByEmail($email);

            $this->addFlash('success', 'Si votre adresse email existe, vous recevez un mail pour réinitialiser votre mot de passe.');

            if($user) {
                $token = bin2hex(random_bytes(15));
                $user->setToken($token);

                $date = new DateTime();
                $date->modify('+10 minutes');

                $user->setTokenExpireAt($date);
                $this->em->flush();



                $mail = new Mail();
                $vars = [
                    'link' => $this->generateUrl('app_password_update', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL),
                ];
                $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Modification de votre mot de passe', "forgotpassword.html", $vars);
            }
        }
        return $this->render('password/index.html.twig', [
            'forgotPaswwordForm' => $form->createView(),
        ]);
    }

    #[Route('/mot-de-passe/reset/{token}', name: 'app_password_update')]
    public function update(Request $request, $token, UserRepository $userRepository): Response
    {

        if(!$token){
            return $this->redirectToRoute('app_password');
        }

        $user = $userRepository->findOneByToken($token);
        $now = new DateTime();

        if(!$user || $user->getTokenExpireAt() < $now){
            return $this->redirectToRoute('app_password');
        }


        $form = $this->createForm(ResetPasswordFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setTokenExpireAt(null);
            $this->em->flush();
            $this->addFlash(
                'success',
                'Votre mot de passe est correctement mis à jour.'
            );

            return $this->redirectToRoute('app_login');
        }
        return $this->render('password/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
