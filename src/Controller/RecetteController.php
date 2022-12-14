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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
    #[IsGranted('ROLE_USER')]
    public function index(ReceipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->findBy(["user" => $this->getUser()]);
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
    #[IsGranted('ROLE_USER')]
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recette = new Receipe();
        $form = $this->createForm(ReceipeType::class, $recette);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $recette = $form->getData();
                $recette->setUser($this->getUser());
                $manager->persist($recette);

                $manager->flush();
                $this->addFlash(
                    'success',
                    'Recette ajout??e !'
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
    #[Security("is_granted('ROLE_USER') and user === recettes.getUser()")]
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
                    'recettes a ??t?? modifi?? !'
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
    #[Security("is_granted('ROLE_USER') and user === recette.getUser()")]
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
                'La recette n\'existe pas en base de donn??es'
            );
            return $this->redirectToRoute('recette.index');
        }
        $manager->remove($recette);
        $manager->flush();

        $this->addFlash(
            'success',
            'La recette a ??t?? supprim?? de la base de donn??es'
        );

        return $this->redirectToRoute('recette.index');
    }

    #[Route('recettes/show/{id}', 'recette.show', methods: ['GET'])]
    #[Security("user === recette.getUser() or recette.isIsPublic() == 1")]
    public function show(EntityManagerInterface $manager, Receipe $recette): Response
    {

        return $this->render('pages/recette/show.html.twig', [
            'controller_name' => 'RecetteController',
            'recette'         => $recette
        ]);
    }

    #[Route('recettes/index', 'recette.index', methods: ['GET'])]
    public function indexPublic(ReceipeRepository $repository,PaginatorInterface $paginator,Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findPublicreceipe(null),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recette/indexpublic.html.twig', [
            'controller_name' => 'RecetteController',
            'recettes'         => $recettes
        ]);
    }
}
