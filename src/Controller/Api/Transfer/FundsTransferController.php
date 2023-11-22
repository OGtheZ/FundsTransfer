<?php

namespace App\Controller\Api\Transfer;

use App\Entity\Transaction;
use App\Form\Transaction\TransactionApiType;
use App\Manager\CurrencyExchangeRateManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FundsTransferController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private CurrencyExchangeRateManagerInterface $rateManager){}

    #[Route('/api/transfer-funds', name: 'api_funds_transfer', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $transaction = new Transaction();
        $transactionForm = $this->createForm(TransactionApiType::class, $transaction);
        $transactionForm->submit($request->request->all());

        if($transactionForm->isSubmitted() && $transactionForm->isValid())
        {
            $accountTo = $transaction->getAccountTo();
            $accountFrom = $transaction->getAccountFrom();
            $currency = $transaction->getCurrency();
            $amount = $transaction->getAmount();
            $deductibleAmount = $amount;

            if($currency !== $accountTo->getCurrency())
            {
                return new JsonResponse(['error' => 'This account does not accept this currency: ' . $currency->getName()], 400);
            }
            // convert currency if needed
            if($accountFrom->getCurrency() !== $currency)
            {
                // amount of transaction and balance stored in account should always be in cents as nominal value, *100 since apilayer accepts floats of whole units of currency
                $exchange = $this->rateManager->convert($currency->getCode(), $accountFrom->getCurrency()->getCode(), $amount/100);
                $deductibleAmount = (int)ceil($exchange*100);
            }
            // check if enough funds are available
            if($accountFrom->getBalance() < $deductibleAmount)
            {
                return new JsonResponse(['error' => 'Insufficient funds.'], 400);
            }

            $accountTo->setBalance($accountTo->getBalance() + $amount);
            $accountTo->addIncomingTransaction($transaction);

            $accountFrom->setBalance($accountFrom->getBalance() - $deductibleAmount);
            $accountFrom->addOutgoingTransaction($transaction);

            $this->entityManager->persist($transaction);
            $this->entityManager->persist($accountFrom);
            $this->entityManager->persist($accountTo);
            $this->entityManager->flush();

            return new JsonResponse(['success' => 'You have successfully transferred '. $amount/100 . ' ' . $currency->getName()]);
        } else{
            $errors = [];
            foreach ($transactionForm->getErrors(true) as $error)
            {
                $errors[$error->getOrigin()->getName()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        }
    }
}
