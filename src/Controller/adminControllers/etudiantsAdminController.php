<?php

namespace App\Controller\adminControllers;

use App\Entity\Etudiant;
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
class etudiantsAdminController extends AbstractController {

    /**
     * @Route("/admin/etudiants", name="admin.etudiants.list")
     * @param Environment $twig
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showList(Environment $twig,  EntityManagerInterface $manager)
    {
        $promotionActuelle = $manager->getRepository(Promotion::class)->findOneBy(['actuelle' => true]);
        $classesActuelles = $promotionActuelle->getClasses();
        $groupesActuels = [];
        foreach($classesActuelles as $classe){
            foreach($classe->getGroupes() as $groupe){ $groupesActuels[] = $groupe; }
        }
        $etudiantsActuels = [];
        foreach($groupesActuels as $groupe){
            foreach($groupe->getEtudiants() as $etudiant){ $etudiantsActuels[] = $etudiant; }
        }
        $etudiants = $manager->getRepository(Etudiant::class)->findAll();
        return new Response($twig->render('Admin/etudiantsAdmin/etudiantsAdminList.html.twig', ["etudiants" => $etudiants, "etudiantsActuels" => $etudiantsActuels]));
    }

    /**
     * @Route("/admin/etudiants/show/{id}", name="admin.etudiants.show")
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
        $etudiant = $manager->getRepository(Etudiant::class)->findOneBy(['id' => $id]);
        if($etudiant != null){
            return new Response($twig->render('Admin/etudiantsAdmin/etudiantsAdminShow.html.twig', ["etudiant" => $etudiant]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }

    /**
     * @Route("/admin/etudiants/create", name="admin.etudiants.create")
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(Environment $twig): Response
    {
        return new Response($twig->render('Admin/etudiantsAdmin/etudiantsAdminCreate.html.twig'));
    }

    /**
     * @Route("/admin/etudiants/{id}", name="admin.etudiants.edit")
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
        $etudiant = $manager->getRepository(Etudiant::class)->findOneBy(['id' => $id]);
        if($etudiant != null){
            return new Response($twig->render('Admin/etudiantsAdmin/etudiantsAdminEdit.html.twig', ["etudiant" => $etudiant]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}