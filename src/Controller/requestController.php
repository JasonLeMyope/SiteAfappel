<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Matiere;
use App\Entity\Professeur;
use App\Entity\Promotion;
use App\Entity\Seance;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @package App\Controller
 */
class requestController extends AbstractController {

    /**
     * @Route("/request/inscription/student/{ine}/{birthdate}", name="requete.inscriptionStudent", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param null $ine
     * @param null $birthdate
     * @return JsonResponse|null
     */
    public function inscriptionEtudiant(Request $request, EntityManagerInterface $manager, $ine = null, $birthdate = null): ?JsonResponse
    {
        $etudiant = $manager->getRepository(Etudiant::class)->findOneBy(['dateNaissance' => $birthdate, 'ine' => $ine]);
        if($etudiant != null){
            $donneesRenvoyes = ['id' => $etudiant->getId()];
            $reponse = new JsonResponse($donneesRenvoyes);
            return $reponse;
        }
        return $this->generateErrorJSON("Erreur 11 : L'étudiant n'a pas été trouvé dans la base de données.");
    }

    /**
     * @Route("/request/inscription/teacher/{arpege}/{birthdate}", name="requete.inscriptionTeacher", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param null $arpege
     * @param null $birthdate
     * @return JsonResponse|null
     */
    public function inscriptionTeacher(Request $request, EntityManagerInterface $manager, $arpege = null, $birthdate = null): ?JsonResponse
    {
        $professeur = $manager->getRepository(Professeur::class)->findOneBy(['dateNaissance' => $birthdate, 'arpege' => $arpege]);
        if($professeur != null){
            $donneesRenvoyes = ['id' => $professeur->getId()];
            $reponse = new JsonResponse($donneesRenvoyes);
            return $reponse;
        }
        return $this->generateErrorJSON("Erreur 12 : L'enseignant n'a pas été trouvé dans la base de données.");
    }

    /**
     * @Route("/request/session/{id}", name="requete.creationSession", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param null $id
     */
    public function creationSession(Request $request, EntityManagerInterface $manager, $id = null): JsonResponse
    {
        $professeur = $manager->getRepository(Professeur::class)->findOneBy(['id' => $id]);
        if($professeur != null){
            $matieres = $professeur->getMatieres();
            $promotionActuelle = $manager->getRepository(Promotion::class)->findOneBy(['actuelle' => true]);
            $classes = $promotionActuelle->getClasses();
            $groupes = [];
            for($i=0;$i<count($classes);$i++){
                $groupesClasse = $classes[$i]->getGroupes();
                for($j=0;$j<count($groupesClasse);$j++){
                    $verif = true;
                    for($k=0;$k<count($groupes);$k++){
                        if($groupesClasse[$j]->getId() == $groupes[$k]->getId()){
                            $verif = false;
                            break;
                        }
                    }
                    if($verif){ $groupes[] = $groupesClasse[$j];}
                }
            }
            $matieresEnvoyees = [];
            $groupesEnvoyes = [];
            for($i=0;$i<count($matieres);$i++){
                $matieresEnvoyees[] = ['id' => $matieres[$i]->getId(), 'label' => $matieres[$i]->getNomMatiere()];
            }
            for($i=0;$i<count($groupes);$i++){
                $groupesEnvoyes[] = ['id' => $groupes[$i]->getId(), 'label' => $groupes[$i]->getNomGroupe()];
            }
            $donneesRenvoyes = ['disciplines' => $matieresEnvoyees, 'groups' => $groupesEnvoyes];
            $reponse = new JsonResponse($donneesRenvoyes);
            return $reponse;
        }
        return $this->generateErrorJSON("Erreur 12 : L'enseignant n'a pas été trouvé dans la base de données.");
    }

    /**
     * @Route("/request/group/{id}", name="requete.getGroupe", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param null $id
     * @return null
     */
    public function getGroupe(Request $request, EntityManagerInterface $manager, $id = null): ?JsonResponse
    {
        $groupe = $manager->getRepository(Groupe::class)->findOneBy(['id' => $id]);
        $etudiantsEnvoyes = [];
        if($groupe != null){
            $etudiants = $groupe->getEtudiants();
            for($i=0;$i<count($etudiants);$i++){
                $etudiantsEnvoyes[] = ['id' => $etudiants[$i]->getId(), 'prenomEtudiant' => $etudiants[$i]->getPrenomEtudiant(), 'nomEtudiant' => $etudiants[$i]->getNomEtudiant()];
            }
            $donneesRenvoyes = ['students' => $etudiantsEnvoyes];
            $reponse = new JsonResponse($donneesRenvoyes);
            return $reponse;
        }
        return $this->generateErrorJSON("Erreur 13 : Le groupe n'a pas été trouvé dans la base de données.");
    }

    /**
     * @Route("/request/call/validate", name="requete.validationAppel", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     */
    public function validationAppel(Request $request, EntityManagerInterface $manager): JsonResponse
    {
        $data = $this->getDataFromJSON($request);
        if($data != null){
            $seance = new Seance();
            $date = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
            $seance->setDate($date);
            for($i=0;$i<count($data['groupsId']);$i++){
                $groupe = $manager->getRepository(Groupe::class)->findOneBy(['id' => $data['groupsId'][$i]]);
                if($groupe != null){ $seance->addGroupe($groupe); }
                else { return $this->generateErrorJSON("Erreur 13 : Le groupe n'a pas été trouvé dans la base de données."); }
            }
            $professeur = $manager->getRepository(Professeur::class)->findOneBy(['id' => $data['teacherId']]);
            if($professeur != null){ $seance->setProfesseur($professeur); }
            else{ return $this->generateErrorJSON("Erreur 12 : L'enseignant n'a pas été trouvé dans la base de données."); }
            $matiere = $manager->getRepository(Matiere::class)->findOneBy(['id' => $data['disciplineId']]);
            if($matiere != null){ $seance->setMatiere($matiere); }
            else{ return $this->generateErrorJSON("Erreur 14 : La matière n'a pas été trouvée dans la base de données."); }
            for($i=0;$i<count($data['attendances']);$i++){
                $etudiant = $manager->getRepository(Etudiant::class)->findOneBy(['id' => $data['attendances'][$i]['id']]);
                if($etudiant != null){
                    if(!$data['attendances'][$i]['presence']){
                        $absence = new Absence();
                        $etudiantAbsent = $manager->getRepository(Etudiant::class)->findOneBy(['id' => $data['attendances'][$i]['id']]);
                        $absence->setEtudiant($etudiantAbsent);
                        $absence->setJustifiee(false);
                        $absence->setJustification("");
                        $manager->persist($absence);
                        $seance->addAbsence($absence);
                    }
                }
                else{ return $this->generateErrorJSON("Erreur 11 : L'étudiant n'a pas été trouvé dans la base de données."); }
            }
            $manager->persist($seance);
            $manager->flush();
            $donneesRenvoyes = ['ok' => true];
            $reponse = new JsonResponse($donneesRenvoyes);
            return $reponse;
        }
        return $this->generateErrorJSON("Erreur 0 : Le JSON envoyé n'a pas pu être décodé.");
    }

    private function getDataFromJSON(Request $request): ?array
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
            return $data;
        }
        return null;
    }

    private function generateErrorJSON($msg): JsonResponse
    {
        $msg = $msg . "\nSi vous constatez cette erreur, veuillez contacter l'administrateur du site web Afappel.";
        $donneesRenvoyes = ['requestError' => $msg];
        $reponse = new JsonResponse($donneesRenvoyes);
        return $reponse;
    }
}