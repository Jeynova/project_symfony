<?php

namespace App\Controller;

use App\Repository\ReceipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Receipe;
use App\Form\ReceipeType;

/**
 * Read Receipes
 *
 * @param ReceipeRepository $repository
 * @param PaginatorInterface $paginator
 * @param Request $request
 * @return Response
 */
class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'recette.index', methods: ['GET'])]
    public function index(ReceipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->findAll();
        $recettes = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recette/index.html.twig', [
            'controller_name' => 'RecetteController',
            'recettes'  =>  $recettes,
        ]);
    }
    #[Route('/recette/new', "recette.new", methods: ['GET', 'POST'])]
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recettes = new Receipe();
        $form = $this->createForm(ReceipeType::class, $recettes);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $recette = $form->getData();
                $manager->persist($recette);

                $manager->flush();
                $this->addFlash(
                    'success',
                    'Recette ajoutée !'
                );
                return $this->redirectToRoute("recette.index");
            } else {
                $this->addFlash(
                    'danger',
                    'Il y a eu une erreur lors de l\'ajout !'
                );
                return $this->redirectToRoute("recette.new");
            }
        }
        return $this->render("pages/recette/new.html.twig", [
            "form"  => $form->createView()
        ]);
    }
    #[Route('/recette/edit/{id}', "recette.edit", methods: ['GET', 'POST'])]
    /**
     * Undocumented function
     *
     * @param Receipe $recettes
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Receipe $recettes, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ReceipeType::class, $recettes);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $recette = $form->getData();
                $manager->persist($recette);

                $manager->flush();
                $this->addFlash(
                    'success',
                    'recettes a été modifié !'
                );
                return $this->redirectToRoute("recette.index");
            } else {
                $this->addFlash(
                    'danger',
                    'Il y a eu une erreur lors de l\'ajout !'
                );
                /* return $this->redirectToRoute("recette.new"); */
            }
        }
        return $this->render("pages/recette/edit.html.twig", [
            "form"  => $form->createView()
        ]);
    }
    #[Route('recettes/delete/{id}', 'recette.delete', methods: ['POST', 'GET'])]
    /**
     * Undocumented function
     *
     * @param EntityManagerInterface $manager
     * @param Receipe $recette
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, Receipe $recette): Response
    {

        if (!$recette) {
            $this->addFlash(
                'danger',
                'La recette n\'existe pas en base de données'
            );
            return $this->redirectToRoute('recette.index');
        }
        $manager->remove($recette);
        $manager->flush();

        $this->addFlash(
            'success',
            'La recette a été supprimé de la base de données'
        );

        return $this->redirectToRoute('recette.index');
    }
}
