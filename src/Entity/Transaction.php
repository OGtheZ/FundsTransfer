<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Currency $currency;

    #[ORM\ManyToOne(inversedBy: 'outgoingTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $accountFrom;

    #[ORM\ManyToOne(inversedBy: 'incomingTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $accountTo;

    #[ORM\Column]
    private int $amount;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAccountFrom(): Account
    {
        return $this->accountFrom;
    }

    public function setAccountFrom(Account $accountFrom): static
    {
        $this->accountFrom = $accountFrom;

        return $this;
    }

    public function getAccountTo(): Account
    {
        return $this->accountTo;
    }

    public function setAccountTo(Account $accountTo): static
    {
        $this->accountTo = $accountTo;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
