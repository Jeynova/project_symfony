<?php

namespace App\Controller;

use App\Repository\ReceipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class HomeController extends AbstractController{

    #[Route('/','home.index',methods:['GET'])]
    public function index(ReceipeRepository $repository,PaginatorInterface $paginator,Request $request):Response{

        $recettes = $paginator->paginate(
            $repository->findPublicreceipe(3),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
       return $this->render("pages/home.html.twig",[
        'controller_name' => 'RecetteController',
        "recettes"  =>  $recettes
       ]);
    }

    


}

