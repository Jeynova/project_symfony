<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    #[Route('/ingredient', name: 'app_ingredient', methods:['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        /* $ingredients = $repository->findAll(); */
        $query = $repository->findAll();
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
    public function new(Request $request, EntityManagerInterface $manager):Response
    {
    $ingredients = new Ingredient();
    $form = $this->createForm(IngredientType::class, $ingredients);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $ingredient = $form->getData();
        $manager->persist($ingredient);

        $manager->flush();
        return $this->redirectToRoute("app_ingredient");
    }

        return $this->render("pages/ingredient/new.html.twig",[
            "form"  =>$form->createView()
        ]);
    }
}
