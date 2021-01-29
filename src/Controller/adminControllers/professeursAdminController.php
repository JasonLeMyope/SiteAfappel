<?php

namespace App\Controller\adminControllers;

use App\Entity\Professeur;
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
class professeursAdminController extends AbstractController {

    /**
     * @Route("/admin/professeurs", name="admin.professeurs.list")
     * @param Environment $twig
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showList(Environment $twig,  EntityManagerInterface $manager)
    {
        $professeurs = $manager->getRepository(Professeur::class)->findAll();
        return new Response($twig->render('Admin/professeursAdmin/professeursAdminList.html.twig', ["professeurs" => $professeurs]));
    }

    /**
     * @Route("/admin/professeurs/show/{id}", name="admin.professeurs.show")
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
        $professeur = $manager->getRepository(Professeur::class)->findOneBy(['id' => $id]);
        if($professeur != null){
            return new Response($twig->render('Admin/professeursAdmin/professeursAdminShow.html.twig', ["professeur" => $professeur]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }

    /**
     * @Route("/admin/professeurs/create", name="admin.professeurs.create")
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(Environment $twig): Response
    {
        return new Response($twig->render('Admin/professeursAdmin/professeursAdminCreate.html.twig'));
    }

    /**
     * @Route("/admin/professeurs/{id}", name="admin.professeurs.edit")
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
        $professeur = $manager->getRepository(Professeur::class)->findOneBy(['id' => $id]);
        if($professeur != null){
            return new Response($twig->render('Admin/professeursAdmin/professeursAdminEdit.html.twig', ["professeur" => $professeur]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}