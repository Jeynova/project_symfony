<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'user.edit',methods:['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and user === entityUser")]
    /**
     * Undocumented function
     *
     * @param User $entityUser
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function edit(User $entityUser,EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response
    {

        $form = $this->createForm(UserType::class, $entityUser);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                if($hasher->isPasswordValid($entityUser, $form->getData()->getPlainPassword())){
                    $entityUser = $form->getData();
                    $manager->persist($entityUser);
    
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
                    return $this->redirectToRoute("user.edit", ['id' => $entityUser->getId()]);
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
    #[Security("is_granted('ROLE_USER') and user === entityUser")]
    public function editPassword(User $entityUser,EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response
    {

        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                if($hasher->isPasswordValid($entityUser, $form->getData()["plainPassword"])){
                    $entityUser->setPassword("change");
                    $entityUser->setCreatedat(new \DateTimeImmutable());
                    $entityUser->setPlainPassword($form->getData()["newPassword"]);

                    $manager->persist($entityUser);
    
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
                    return $this->redirectToRoute("user.edit.password", ['id' => $entityUser->getId()]);
                }
                
            } else {
                $this->addFlash(
                    'danger',
                    'Il y a eu une erreur lors de la modification !'
                );
                return $this->redirectToRoute("user.edit.password",['id' => $entityUser->getId()]);
            }
        }
        return $this->render('pages/user/edit_password.html.twig', [
            'controller_name' => 'UserController',
            'form'  =>  $form->createView()
        ]);
    }
}
