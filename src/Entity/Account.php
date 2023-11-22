<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{#[Groups(['account', 'transaction'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Groups('account')]
    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private int $balance;

    #[Groups('account')]
    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private Currency $currency;

    #[Groups(['account', 'transaction'])]
    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\OneToMany(mappedBy: 'accountFrom', targetEntity: Transaction::class)]
    private Collection $outgoingTransactions;

    #[ORM\OneToMany(mappedBy: 'accountTo', targetEntity: Transaction::class)]
    private Collection $incomingTransactions;

    public function __construct()
    {
        $this->outgoingTransactions = new ArrayCollection();
        $this->incomingTransactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): static
    {
        $this->balance = $balance;

        return $this;
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

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getOutgoingTransactions(): Collection
    {
        return $this->outgoingTransactions;
    }

    public function addOutgoingTransaction(Transaction $outgoingTransaction): static
    {
        if (!$this->outgoingTransactions->contains($outgoingTransaction)) {
            $this->outgoingTransactions->add($outgoingTransaction);
            $outgoingTransaction->setAccountFrom($this);
        }

        return $this;
    }

    public function removeOutgoingTransaction(Transaction $outgoingTransaction): static
    {
        $this->outgoingTransactions->removeElement($outgoingTransaction);

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getIncomingTransactions(): Collection
    {
        return $this->incomingTransactions;
    }

    public function addIncomingTransaction(Transaction $incomingTransaction): static
    {
        if (!$this->incomingTransactions->contains($incomingTransaction)) {
            $this->incomingTransactions->add($incomingTransaction);
            $incomingTransaction->setAccountTo($this);
        }

        return $this;
    }

    public function removeIncomingTransaction(Transaction $incomingTransaction): static
    {
        $this->incomingTransactions->removeElement($incomingTransaction);

        return $this;
    }
}
