<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/series", name="serie_")
 */
class SerieController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(SerieRepository $serieRepository): Response
    {
        $series =$serieRepository->findBestSeries();

        return $this->render('serie/list.html.twig', [
            "series"=> $series
        ]);
    }

    /**
    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie){
            throw $this->createNotFoundException('oh no!!!');
        }

        return $this->render('serie/details.html.twig', [
            "serie" => $serie
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request,
                           EntityManagerInterface $entityManager): Response
    {
        //creation d'une nouvelle serie
        $serie = new Serie();
        //mise en place de la date du jour de creation de la serie
        $serie->setDateCreated(new \DateTime());

        //creation du formulaire
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        //verification de la validation du formulaire de creation de saison
        if ($serieForm->isSubmitted() && $serieForm->isValid()){
            $entityManager->persist($serie);
            $entityManager->flush();

            //message flash
            $this->addFlash('success', 'Serie added! Good job.');
            return $this->redirectToRoute('serie_details', ['id' => $serie->getId()]);
        }

        return $this->render('serie/create.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    /**
     * @Route("/demo", name="em_demo")
     */
    public function demo(EntityManagerInterface $entityManager): Response
    {
        //création d'une instence de l'entité
        $serie = new Serie();

        //hydrate toutes les propriétés
        $serie->setName('pif');
        $serie->setBackdrop('dafsd');
        $serie->setPoster('dafsdgg');
        $serie->setDateCreated(new \DateTime());
        $serie->setFirstAirDate(new \DateTime("-1year"));
        $serie->setLastAirDate(new \DateTime('-6 mouth'));
        $serie->setGenres('drama');
        $serie->setOverview('blablable');
        $serie->setPopularity(123.00);
        $serie->setVote(8.2);
        $serie->setStatus('cancelled');
        $serie->setTmdbId(123456);

        dump($serie);

        //-------------------------------------inserer les donnée dans la BDD----------------------------------
        $entityManager->persist($serie);
        $entityManager->flush();
        //$entityManager = $this->getDoctrine()->getManager();

        //-------------------------------------Modifier une donne de la BDD-------------------------------------
        //$serie->setGenres('comedie');
        //$entityManager->flush();


        return $this->render('serie/create.html.twig', [

        ]);
    }
        //--------------------------------------supprimer la serie---------------------------------------------
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Serie $serie, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }

}
