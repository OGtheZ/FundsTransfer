<?php

namespace App\Manager;

interface CurrencyExchangeRateManagerInterface
{
    public function convert(string $currencyFrom, string $currencyTo, int $amount): array;
}