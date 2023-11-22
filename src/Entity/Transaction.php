<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[Groups('transaction')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Groups('transaction')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Currency $currency;

    #[Groups('transaction')]
    #[ORM\ManyToOne(inversedBy: 'outgoingTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $accountFrom;

    #[Groups('transaction')]
    #[ORM\ManyToOne(inversedBy: 'incomingTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $accountTo;

    #[Groups('transaction')]
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
