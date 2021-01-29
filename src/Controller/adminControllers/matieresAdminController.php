<?php

namespace App\Controller\adminControllers;

use App\Entity\Matiere;
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
class matieresAdminController extends AbstractController {

    /**
     * @Route("/admin/matieres", name="admin.matieres.list")
     * @param Environment $twig
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showList(Environment $twig,  EntityManagerInterface $manager)
    {
        $matieres = $manager->getRepository(Matiere::class)->findAll();
        return new Response($twig->render('Admin/matieresAdmin/matieresAdminList.html.twig', ["matieres" => $matieres]));
    }

    /**
     * @Route("/admin/matieres/show/{id}", name="admin.matieres.show")
     * @param Environment $twig
     * @param EntityManagerInterface $manager
     * @param null $id
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(Environment  $twig, EntityManagerInterface $manager, $id = null): Response
    {
        $matiere = $manager->getRepository(Matiere::class)->findOneBy(['id' => $id]);
        if($matiere != null){
            return new Response($twig->render('Admin/matieresAdmin/matieresAdminShow.html.twig', ["matiere" => $matiere]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }

    /**
     * @Route("/admin/matieres/create", name="admin.matieres.create")
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(Environment $twig): Response
    {
        return new Response($twig->render('Admin/matieresAdmin/matieresAdminCreate.html.twig'));
    }

    /**
     * @Route("/admin/matieres/{id}", name="admin.matieres.edit")
     * @param Environment $twig
     * @param EntityManagerInterface $manager
     * @param null $id
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(Environment $twig,  EntityManagerInterface $manager, $id = null)
    {
        $matiere = $manager->getRepository(Matiere::class)->findOneBy(['id' => $id]);
        if($matiere != null){
            return new Response($twig->render('Admin/matieresAdmin/groupesAdminEdit.html.twig', ["matiere" => $matiere]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}