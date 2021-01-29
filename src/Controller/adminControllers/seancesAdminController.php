<?php

namespace App\Controller\adminControllers;

use App\Entity\Promotion;
use App\Entity\Seance;
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
class seancesAdminController extends AbstractController {

    /**
     * @Route("/admin/seances", name="admin.seances.list")
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
        $seancesActuelles = [];
        foreach($groupesActuels as $groupe){
            foreach($groupe->getSeances() as $seance){
                $verif = true;
                foreach($seancesActuelles as $seanceActuelle){
                    if($seanceActuelle->getId() == $seance->getId()){
                        $verif = false;
                        break;
                    }
                }
                if($verif){ $seancesActuelles[] = $seance; }
            }
        }
        $seances = $manager->getRepository(Seance::class)->findAll();
        return new Response($twig->render('Admin/seancesAdmin/seancesAdminList.html.twig', ["seances" => $seances, "seancesActuelles" => $seancesActuelles]));
    }

    /**
     * @Route("/admin/seances/show/{id}", name="admin.seances.show")
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
        $seance = $manager->getRepository(Seance::class)->findOneBy(['id' => $id]);
        if($seance != null){
            return new Response($twig->render('Admin/seancesAdmin/seancesAdminShow.html.twig', ["seance" => $seance]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }

    /**
     * @Route("/admin/seances/{id}", name="admin.seances.edit")
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
        $seance = $manager->getRepository(Seance::class)->findOneBy(['id' => $id]);
        if($seance != null){
            return new Response($twig->render('Admin/seancesAdmin/seancesAdminEdit.html.twig', ["seances" => $seance]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}