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
    public function __construct(private HttpClientInterface $httpClient){}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function convert(string $currencyFrom, string $currencyTo, int $amount): int
    {
        $url = "https://api.apilayer.com/currency_data/convert?to=".$currencyTo."&from=".$currencyFrom."&amount=".$amount/100;
        $headers = [
            'Content-Type' => 'text/plain',
            'apikey' => $_ENV['EXCHANGE_RATE_API_KEY']
        ];

        $response = $this->httpClient->withOptions(['headers' => $headers])->request('GET', $url);

        return (int)ceil($response->toArray()['result']*100);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function loadCurrencies(): array
    {
        $url = "https://api.apilayer.com/currency_data/list";
        $headers = [
            'Content-Type' => 'text/plain',
            'apikey' => $_ENV['EXCHANGE_RATE_API_KEY']
        ];

        $response = $this->httpClient->withOptions(['headers' => $headers])->request('GET', $url);
        $responseArray = $response->toArray();
        return $responseArray['currencies'];
    }
}