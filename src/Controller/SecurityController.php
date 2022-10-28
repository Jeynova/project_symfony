<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationType;
use App\Entity\User;

class SecurityController extends AbstractController
{

/*     public function index(): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    } */
    #[Route('/connexion', name: 'security.login', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route("/deconnexion", name: "security.logout")]
    public function logout()
    {
    }

    #[Route("/inscription", name: "security.registration" ,methods :['GET','POST'])]
    public function registration(Request $request, EntityManagerInterface $manager) : Response{

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            if($form->isValid()){
                $user = $form->getData();
                $manager->persist($user);
        
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Utilisateur ajouté !'
                );
                return $this->redirectToRoute("security.login");
            }
            else{
                $this->addFlash(
                    'danger',
                    'Il y a eu une erreur lors de l\'inscription !'
                );
                /* return $this->redirectToRoute("security.registration"); */
            }
    
        }
        return $this->render('pages/security/registration.html.twig',[
            "form"  =>$form->createView()
        ]);
    }
}
