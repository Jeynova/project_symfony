<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**
     * Read Ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods:['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        /* $ingredients = $repository->findAll(); */
        $query = $repository->findBy(["user" => $this->getUser()]);
        $ingredients = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
            'ingredients'   =>  $ingredients
        ]);
    }

    #[Route('/ingredient/new', "ingredient.new", methods:['GET','POST'])]
    #[IsGranted('ROLE_USER')]
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager):Response
    {
    $ingredient = new Ingredient();
    $form = $this->createForm(IngredientType::class, $ingredient);

    $form->handleRequest($request);


    if ($form->isSubmitted()) {
        if($form->isValid()){
            $ingredient = $form->getData();
            $ingredient->setUser($this->getUser());
            $manager->persist($ingredient);
    
            $manager->flush();
            $this->addFlash(
                'success',
                'Ingredients ajouté !'
            );
            return $this->redirectToRoute("ingredient.index");
        }
        else{
            $this->addFlash(
                'danger',
                'Il y a eu une erreur lors de l\'ajout !'
            );
            return $this->redirectToRoute("ingredient.new");
        }

    }
        return $this->render("pages/ingredient/new.html.twig",[
            "form"  =>$form->createView()
        ]);
    }
    #[Route('/ingredient/edit/{id}', "ingredient.edit", methods:['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and user === ingredients.getUser()")]
    /**
     * Undocumented function
     *
     * @param Ingredient $ingredients
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Ingredient $ingredients,Request $request, EntityManagerInterface $manager) : Response{
        $form = $this->createForm(IngredientType::class, $ingredients);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            if($form->isValid()){
                $ingredient = $form->getData();
                $manager->persist($ingredient);
        
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Ingredients a été modifié !'
                );
                return $this->redirectToRoute("ingredient.index");
            }
            else{
                $this->addFlash(
                    'danger',
                    'Il y a eu une erreur lors de l\'ajout !'
                );
                return $this->redirectToRoute("ingredient.new");
            }
        }
        return $this->render("pages/ingredient/edit.html.twig",[
            "form"  => $form->createView()
        ]);
    }
    #[Route('ingredients/delete/{id}', 'ingredient.delete' ,methods:['POST','GET'])]
    #[Security("is_granted('ROLE_USER') and user === ingredients.getUser()")]
    /**
     * Undocumented function
     *
     * @param EntityManagerInterface $manager
     * @param Ingredient $ingredient
     * @return Response
     */
    public function delete(EntityManagerInterface $manager,Ingredient $ingredient) : Response{

        if(!$ingredient){
            $this->addFlash(
                'danger',
                'L\'ingredient n\'existe pas en base de données'
            );
            return $this->redirectToRoute('ingredient.index');
        }
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'L\'ingredient a été supprimé de la base de données'
        );

        return $this->redirectToRoute('ingredient.index');

    }
}
