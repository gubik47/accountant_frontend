<?php

namespace App\Service\Api;

use App\Utils\Api\RequestBlueprint;
use App\Utils\Api\ResourceData;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiClient
{
    private HttpClientInterface $client;

    private ResourceData $data;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client->withOptions([
            "base_uri" => "http://api.accountant.local/api/",
            "timeout" => 30
        ]);
    }
    public function sendRequests(array $blueprints): ResourceData
    {
        $this->data = new ResourceData();

        $responses = $this->createRequests($blueprints);

        foreach ($responses as $containerName => $response) {
            if ($response->getStatusCode() === Response::HTTP_NOT_FOUND) {
                throw new NotFoundHttpException();
            }

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new RuntimeException("Unexpected API error");
            }

            $json = json_decode($response->getContent(), true);
            if ($json === null) {
                throw new RuntimeException("Unexpected API error");
            }

            $this->data->{$containerName} = $json;
        }

        return $this->data;
    }

    /**
     * @param RequestBlueprint[] $blueprints
     * @return ResponseInterface[]
     */
    private function createRequests(array $blueprints): array
    {
        $responses = [];
        foreach ($blueprints as $blueprint) {
            $options = [];

            if ($bodyData = $blueprint->getBodyData()) {
                $options["json"] = $bodyData;
            }

            $responses[$blueprint->getContainerName()] = $this->client->request($blueprint->getMethod(), $blueprint->getUri(), $options);
        }

        return $responses;
    }
}
