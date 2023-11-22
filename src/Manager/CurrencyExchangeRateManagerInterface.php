<?php

namespace App\Manager;

interface CurrencyExchangeRateManagerInterface
{
    // must return result of conversion as float
    public function convert(string $currencyFrom, string $currencyTo, int $amount): float;

    // must return an array of currencies with key as the code and value as the name
    public function loadCurrencies(): array;
}