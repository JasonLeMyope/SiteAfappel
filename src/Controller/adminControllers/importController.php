<?php

namespace App\Controller\adminControllers;

use App\Entity\Promotion;
use App\Type\ImportType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class importController extends AbstractController {

    /**
     * @Route("/admin/import", name="admin.import")
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param Environment $twig
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function import(Request $request, SluggerInterface $slugger, Environment $twig,  EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('tableau')->getData();
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
            try {
                $brochureFile->move(
                    $this->getParameter('table_directory'),
                    $newFilename
                );
            } catch (FileException $e) {}
            return $this->redirectToRoute('index.index');
        }

        $tabPromotions = $manager->getRepository(Promotion::class)->findAll();
        return $this->render('Admin/adminImport.html.twig', ['form' => $form->createView(), 'promotions' => $tabPromotions]);
    }
}