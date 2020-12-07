<?php

namespace App\Controller\adminControllers;

use App\Entity\Groupe;
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
        $groupes = $manager->getRepository(Groupe::class)->findAll();
        return new Response($twig->render('Admin/groupesAdmin/groupesAdminList.html.twig', ["groupes" => $groupes]));
    }

    /**
     * @Route("/admin/groupes/{id}", name="admin.groupes.edit")
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
        $groupe = $manager->getRepository(Groupe::class)->findOneBy(['id' => $id]);
        if($groupe != null){
            return new Response($twig->render('Admin/groupesAdmin/groupesAdminEdit.html.twig', ["groupe" => $groupe]));
        }
        return new Response($twig->render('404NotFound.html.twig'));
    }
}