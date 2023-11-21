<?php

namespace App\Controller\Api\Client;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClientAccountListController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    #[Route('/api/client/{client}/account/list', name: 'api_client_account_list')]
    public function accountList(Client $client, ): JsonResponse
    {
        $accounts = $client->getAccounts()->getValues();

        return JsonResponse::fromJsonString($this->serializer->serialize($accounts, 'json', ['groups' => ['account']]));
    }
}
