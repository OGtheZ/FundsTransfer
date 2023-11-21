<?php

namespace App\Controller;

use App\Manager\CurrencyExchangeRateManagerInterface;
use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    public function __construct(private CurrencyExchangeRateManagerInterface $rateManager, private CurrencyRepository $currencyRepository){}

    #[Route('/test', name: 'app_test')]
    public function index(): JsonResponse
    {
        $usd = $this->currencyRepository->findOneBy(['code' => 'USD']);
        $euro = $this->currencyRepository->findOneBy(['code' => 'EUR']);
        $frank = $this->currencyRepository->findOneBy(['code' => 'CHF']);
        $currencies = [$usd, $euro, $frank];

        dd($currencies);
    }
}
