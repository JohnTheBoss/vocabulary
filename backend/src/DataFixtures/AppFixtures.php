<?php

namespace App\DataFixtures;

use App\Adapter\AuthenticationAdapter\PasswordHashAdapter;
use App\Entity\Dictionary;
use App\Entity\User;
use App\Entity\Word;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private PasswordHashAdapter $passwordHashAdapter;

    public function __construct(PasswordHashAdapter $passwordHashAdapter)
    {
        $this->passwordHashAdapter = $passwordHashAdapter;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $uReka = new User();
        $uReka->setEmail('matilda.legyek.rekak@gmail.com');
        $uReka->setPassword($this->passwordHashAdapter->encrypt('R3k@123.'));
        $uReka->setFullname('Legyek Réka Matilda');
        $manager->persist($uReka);

        $u2 = new User();
        $u2->setEmail('u2@gmail.com');
        $u2->setPassword($this->passwordHashAdapter->encrypt('User1234.'));
        $u2->setFullname('Test User2');
        $manager->persist($u2);

        $d1 = new Dictionary();
        $d1->setUser($uReka);
        $d1->setName('Angol-Magyar számok');
        $d1->setKnownLanguage('magyar');
        $d1->setForeignLanguage('angol');

        $this->addWords(
            $manager,
            [
                'one' => 'egy',
                'two' => 'kettő',
                'three' => 'három',
                'four' => 'négy',
                'five' => 'öt',
                'six' => 'hat',
                'seven' => 'hét',
                'eight' => 'nyolc',
                'nine' => 'kilenc',
                'ten' => 'tíz',
                'eleven' => 'tizenegy',
                'twelve' => 'tizenkettő',
                'twenty' => 'húsz',
                'thirty' => 'harmic',
                'forty' => 'negyve',
                'fifty' => 'ötven',
                'sixty' => 'hatvan',
                'seventy' => 'hetven',
                'eighty' => 'nyolcvan',
                'ninety' => 'kilencven',
                'hundred' => 'száz',
            ],
            $d1
        );

        $d2 = new Dictionary();
        $d2->setUser($uReka);
        $d2->setName('Angol-Magyar IT szótár');
        $d2->setKnownLanguage('magyar');
        $d2->setForeignLanguage('angol');
        $manager->persist($d2);

        $this->addWords(
            $manager,
            [
                'error' => 'hiba',
                'not found' => 'nem található',
                'class' => 'osztály',
                'inteface' => 'intefész',
            ],
            $d2
        );

        $d3 = new Dictionary();
        $d3->setUser($uReka);
        $d3->setName('Teszt');
        $d3->setKnownLanguage('magyar');
        $d3->setForeignLanguage('magyar');
        $manager->persist($d3);

        $this->addWords($manager, ['1' => '1', '2' => '2', 'a' => 'a', 'b' => 'b'], $d3);

        $manager->flush();
    }

    private function addWords($manager, array $words, Dictionary $dictionary)
    {
        foreach ($words as $foregin => $know) {
            $word = new Word();
            $word->setForeignLanguage($foregin);
            $word->setKnownLanguage($know);
            $word->setDictionary($dictionary);
            $manager->persist($word);
        }
    }
}
