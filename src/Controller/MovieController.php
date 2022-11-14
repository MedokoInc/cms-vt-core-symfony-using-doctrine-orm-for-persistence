<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Quote;
use App\Repository\MovieRepository;
use App\Repository\QuoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movies', name: 'app_movie')]
    public function index(MovieRepository $mr, QuoteRepository $qr): Response
    {
        //return all movies with their relating quotes
        $movies = $mr->findAll();
        $quotes = $qr->findAll();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
            'quotes' => $quotes,
        ]);
    }

    #[Route('/movie/{id}', name: 'app_movie_show')]
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/quotes', name: 'app_quotes')]
    public function quotes(QuoteRepository $qr): Response
    {
        //return all quotes with their relating movies
        $quotes = $qr->findAll();

        return $this->render('movie/quotes.html.twig', [
            'quotes' => $quotes,
        ]);

    }

    #[Route('/quote/delete/{id}', name: 'app_quote_delete')]
    public function delete(Quote $quote, ManagerRegistry $mr): Response
    {
        $em = $mr->getManager();
        $em->remove($quote);
        $em->flush();

        return $this->redirectToRoute('app_quotes');
    }

    #[Route('/quotes/{search}', name: 'app_quotes_search')]
    public function showQuotes(QuoteRepository $qr, String $search): Response
    {
        $quotes = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->where('q.text LIKE %:search%');

        return $this->render('movie/quotes.html.twig', [
            'quotes' => $quotes,
        ]);
    }
}