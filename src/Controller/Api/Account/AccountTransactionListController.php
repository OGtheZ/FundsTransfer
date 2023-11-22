<?php

namespace App\Controller\Api\Account;

use App\Entity\Account;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AccountTransactionListController extends AbstractController
{
    public function __construct(private TransactionRepository $transactionRepository, private SerializerInterface $serializer){}
    #[Route('/api/account/{account}/transaction-list', name: 'api_account_transaction_list')]
    public function __invoke(Account $account, Request $request): JsonResponse
    {
        $page = (int)$request->query->get('page') ?? 1;
        $limit = (int)$request->query->get('limit') ?? 10;
        $transactions = $this->transactionRepository->findAllForAccount($account, $page, $limit);

        return JsonResponse::fromJsonString($this->serializer->serialize($transactions, 'json', ['groups' => ['transaction']]));
    }
}
