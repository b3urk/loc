<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehiculeController extends AbstractController
{

    #[Route('/vehicule', name: 'app_vehicule')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $vehicules = $doctrine->getRepository(Vehicule::class)->findAll();
        return $this->render('vehicule/vehicules.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }

    #[Route('/ajout-vehicule', name: 'app_ajout_vehicule')]
    public function ajout_vehicule(ManagerRegistry $doctrine, Request $request): Response
    {

        $vehicule = new Vehicule();
        $form_vehicule = $this->createForm(VehiculeType::class, $vehicule);
        $form_vehicule->handleRequest($request);

        if ($form_vehicule->isSubmitted() && $form_vehicule->isValid()) {
            $entitManager = $doctrine->getManager();
            $entitManager->persist($vehicule);
            $entitManager->flush();
            return $this->redirectToRoute("app_vehicule");
        }
        return $this->renderForm('vehicule/ajout_vehicule.html.twig', [
            'form_vehicule' => $form_vehicule,
        ]);
    }

    #[Route('/modif-vehicule/{id}', name: 'app_modif_vehicule')]
    public function mofidf_vehicule(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $vehicule = $doctrine->getRepository(Vehicule::class)->find($id);
        $form_vehicule = $this->createForm(VehiculeType::class, $vehicule);
        $form_vehicule->handleRequest($request);

        if ($form_vehicule->isSubmitted() && $form_vehicule->isValid()) {

            $entitManager = $doctrine->getManager();
            $entitManager->persist($vehicule);
            $entitManager->flush();
            return $this->redirectToRoute("app_vehicule");
        }

        return $this->renderForm('vehicule/ajout_vehicule.html.twig', [
            'form_vehicule' => $form_vehicule,
        ]);
    }

    #[Route('/efface-vehicule/{id}', name: 'app_efface_vehicule')]
    public function efface_vehicule(ManagerRegistry $doctrine, $id): Response 
    {
        $vehicule = $doctrine->getRepository(Vehicule::class)->find($id);
        $manager = $doctrine->getManager();
        $manager->remove($vehicule);
        $manager->flush();
        return $this->redirectToRoute("app_vehicule");

    }
}
