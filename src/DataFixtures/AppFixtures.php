<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Quote;
use App\Repository\MovieRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 20; $i > 0; $i--) {
            $movie = new Movie();
            $movie->setName('Movie '.$i);

            $start = new \DateTime('1500-01-01');
            $end = new \DateTime('2500-12-31');
            $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
            $randomDate = new DateTime();
            $randomDate->setTimestamp($randomTimestamp);

            $movie->setRelease($randomDate);
            $manager->persist($movie);
        }
        $manager->flush();

        //get all movie ids
        $movies = $manager->getRepository(Movie::class)->findAll();

        foreach ($movies as $movie) {
            for ($i = 0; $i < 5; $i++) {
                $quote = new Quote();
                $quote->setText('Quote '.$i);
                $quote->setMovie($movie);
                $quote->setCharacter('Character '.$i);
                $manager->persist($quote);
            }
        }
        $manager->flush();
    }
}
