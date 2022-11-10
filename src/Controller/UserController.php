<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'user.edit',methods:['GET','POST'])]
    /**
     * Undocumented function
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function edit(User $user,EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response
    {

        if(!$this->getUser() || $this->getUser() !== $user){
            return $this->redirectToRoute('security.login');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                if($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())){
                    $utilisateur = $form->getData();
                    $manager->persist($utilisateur);
    
                    $manager->flush();
                    $this->addFlash(
                        'success',
                        'Utilisateur modifié !'
                    );
                    return $this->redirectToRoute("recette.index");
                }
                else{
                    $this->addFlash(
                        'danger',
                        'Mot de passe incorrect'
                    );
                    return $this->redirectToRoute("user.edit", ['id' => $user->getId()]);
                }
                
            } else {
                $this->addFlash(
                    'danger',
                    'Il y a eu une erreur lors de la modification !'
                );
                return $this->redirectToRoute("user.edit");
            }
        }
        return $this->render('pages/user/edit.html.twig', [
            'controller_name' => 'UserController',
            'form'  =>  $form->createView()
        ]);
    }

    #[Route('/utilisateur/editpass/{id}', name : 'user.edit.password', methods: ['GET','POST'])]
    public function editPassword(User $user,EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        if(!$this->getUser() || $this->getUser() !== $user){
            return $this->redirectToRoute('security.login');
        }

        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                if($hasher->isPasswordValid($user, $form->getData()["plainPassword"])){
                    $user->setPlainPassword($form->getData()["newPassword"]);

                    $manager->persist($user);
    
                    $manager->flush();
                    $this->addFlash(
                        'success',
                        'Mot de passe modifié !'
                    );
                    return $this->redirectToRoute("recette.index");
                }
                else{
                    $this->addFlash(
                        'danger',
                        'Mot de passe incorrect'
                    );
                    return $this->redirectToRoute("user.edit.password", ['id' => $user->getId()]);
                }
                
            } else {
                $this->addFlash(
                    'danger',
                    'Il y a eu une erreur lors de la modification !'
                );
                return $this->redirectToRoute("user.edit.password",['id' => $user->getId()]);
            }
        }
        return $this->render('pages/user/edit_password.html.twig', [
            'controller_name' => 'UserController',
            'form'  =>  $form->createView()
        ]);
    }
}
