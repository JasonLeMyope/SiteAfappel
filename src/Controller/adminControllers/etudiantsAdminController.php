<?php

namespace App\Controller\adminControllers;

use App\Entity\Etudiant;
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
        $etudiants = $manager->getRepository(Etudiant::class)->findAll();
        return new Response($twig->render('Admin/etudiantsAdmin/etudiantsAdminList.html.twig', ["etudiants" => $etudiants]));
    }

    /**
     * @Route("/admin/etudiants/{id}", name="admin.etudiants.edit")
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
        $etudiant = $manager->getRepository(Etudiant::class)->findOneBy(['id' => $id]);
        if($etudiant != null){
            return new Response($twig->render('Admin/etudiantsAdmin/etudiantsAdminEdit.html.twig', ["etudiant" => $etudiant]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}