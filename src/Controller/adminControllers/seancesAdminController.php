<?php

namespace App\Controller\adminControllers;

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
        $seances = $manager->getRepository(Seance::class)->findAll();
        return new Response($twig->render('Admin/seancesAdmin/seancesAdminList.html.twig', ["seances" => $seances]));
    }

    /**
     * @Route("/admin/seances/{id}", name="admin.seances.edit")
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
        $seance = $manager->getRepository(Seance::class)->findOneBy(['id' => $id]);
        if($seance != null){
            return new Response($twig->render('Admin/seancesAdmin/seancesAdminEdit.html.twig', ["seances" => $seance]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}