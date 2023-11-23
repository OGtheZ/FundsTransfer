<?php

namespace App\Manager;

interface CurrencyExchangeRateManagerInterface
{
    // must return result of conversion as int in cents
    public function convert(string $currencyFrom, string $currencyTo, int $amount): int;

    // must return an array of currencies with key as the code and value as the name
    public function loadCurrencies(): array;
}