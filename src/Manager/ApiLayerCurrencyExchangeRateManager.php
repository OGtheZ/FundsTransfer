<?php

namespace App\Manager;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiLayerCurrencyExchangeRateManager implements CurrencyExchangeRateManagerInterface
{
    public function __construct(private string $apiKey, private HttpClientInterface $httpClient){
        $this->apiKey = $_ENV['EXCHANGE_RATE_API_KEY'];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function convert(string $currencyFrom, string $currencyTo, int $amount): array
    {
        $url = "https://api.apilayer.com/currency_data/convert?to=".$currencyTo."&from=".$currencyFrom."&amount=".$amount;
        $headers = [
            'Content-Type' => 'text/plain',
            'apikey' => $this->apiKey
        ];

        $response = $this->httpClient->withOptions(['headers' => $headers])->request('GET', $url);

        return $response->toArray();
    }
}