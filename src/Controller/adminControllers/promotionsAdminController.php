<?php

namespace App\Controller\adminControllers;

use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class promotionsAdminController extends AbstractController {

    /**
     * @Route("/admin/promotions", name="admin.promotions.list")
     * @param Request $request
     * @param Environment $twig
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showList(Request $request, Environment $twig,  EntityManagerInterface $manager)
    {
        $promotions = $manager->getRepository(Promotion::class)->findAll();
        return new Response($twig->render('Admin/promotionsAdmin/promotionsAdminList.html.twig', ["promotions" => $promotions]));
    }

    /**
     * @Route("/admin/promotions/{id}", name="admin.promotions.edit")
     * @param Request $request
     * @param Environment $twig
     * @param EntityManagerInterface $manager
     * @param null $id
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(Request $request, Environment $twig,  EntityManagerInterface $manager, $id = null)
    {
        $promotion = $manager->getRepository(Promotion::class)->findOneBy(['id' => $id]);
        if($promotion != null){
            return new Response($twig->render('Admin/promotionsAdmin/promotionsAdminEdit.html.twig', ["promotion" => $promotion]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}