<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Client;
use App\Entity\Currency;
use App\Manager\CurrencyExchangeRateManagerInterface;
use App\Repository\CurrencyRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class AppFixtures extends Fixture
{
    public function __construct(private CurrencyRepository $currencyRepository,
                                private CurrencyExchangeRateManagerInterface $rateManager){}

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function load(ObjectManager $manager): void
    {
        $response = $this->rateManager->loadCurrencies();

        foreach ($response as $key => $currency)
        {
            $newCurrency = new Currency();
            $newCurrency->setCode($key);
            $newCurrency->setName($currency);
            $manager->persist($newCurrency);
        }
        $manager->flush();
        $usd = $this->currencyRepository->findOneBy(['code' => 'USD']);
        $euro = $this->currencyRepository->findOneBy(['code' => 'EUR']);
        $frank = $this->currencyRepository->findOneBy(['code' => 'CHF']);
        $currencies = [$usd, $euro, $frank];

        for($i = 0; $i <= 2; $i++)
        {
            $client = new Client();
            $client->setName('John'.$i);
            $client->setLastName('Doe'.$i);
            for($y = 0; $y <= 2; $y++){
                $account = new Account();
                $account->setBalance(1000);
                $account->setClient($client);
                $account->setCurrency($currencies[$y]);
                $client->addAccount($account);
                $manager->persist($account);
            }
            $manager->persist($client);
        }

        $manager->flush();
    }
}
