<?php

namespace App\Controller\adminControllers;

use App\Entity\Absence;
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
class absencesAdminController extends AbstractController {

    /**
     * @Route("/admin/absences", name="admin.absences.list")
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
        $absences = $manager->getRepository(Absence::class)->findAll();
        return new Response($twig->render('Admin/absencesAdmin/absencesAdminList.html.twig', ["absences" => $absences]));
    }

    /**
     * @Route("/admin/absences/{id}", name="admin.absences.edit")
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
        $absence = $manager->getRepository(Absence::class)->findOneBy(['id' => $id]);
        if($absence != null){
            return new Response($twig->render('Admin/absencesAdmin/absencesAdminEdit.html.twig', ["absence" => $absence]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}