<?php

namespace App\Controller\adminControllers;

use App\Entity\Groupe;
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
class groupesAdminController extends AbstractController {

    /**
     * @Route("/admin/groupes", name="admin.groupes.list")
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
            foreach ($classe->getGroupes() as $groupe){ $groupesActuels[] = $groupe; }
        }
        $groupes = $manager->getRepository(Groupe::class)->findAll();
        return new Response($twig->render('Admin/groupesAdmin/groupesAdminList.html.twig', ["groupesActuels" => $groupesActuels, "groupes" => $groupes]));
    }

    /**
     * @Route("/admin/groupes/show/{id}", name="admin.groupes.show")
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
        $groupe = $manager->getRepository(Groupe::class)->findOneBy(['id' => $id]);
        if($groupe != null){
            return new Response($twig->render('Admin/groupesAdmin/groupesAdminShow.html.twig', ["groupe" => $groupe]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }

    /**
     * @Route("/admin/groupes/create", name="admin.groupes.create")
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(Environment $twig): Response
    {
        return new Response($twig->render('Admin/groupesAdmin/groupesAdminCreate.html.twig'));
    }

    /**
     * @Route("/admin/groupes/{id}", name="admin.groupes.edit")
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
        $groupe = $manager->getRepository(Groupe::class)->findOneBy(['id' => $id]);
        if($groupe != null){
            return new Response($twig->render('Admin/groupesAdmin/groupesAdminEdit.html.twig', ["groupe" => $groupe]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}