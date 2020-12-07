<?php

namespace App\Controller\adminControllers;

use App\Entity\Classe;
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
class classesAdminController extends AbstractController {

    /**
     * @Route("/admin/classes", name="admin.classes.list")
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
        $classes = $manager->getRepository(Classe::class)->findAll();
        return new Response($twig->render('Admin/classesAdmin/classesAdminList.html.twig', ["classes" => $classes]));
    }

    /**
     * @Route("/admin/classes/{id}", name="admin.classes.edit")
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
        $classe = $manager->getRepository(Classe::class)->findOneBy(['id' => $id]);
        if($classe != null){
            return new Response($twig->render('Admin/classesAdmin/classesAdminEdit.html.twig', ["classe" => $classe]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}