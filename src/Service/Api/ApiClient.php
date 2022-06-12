<?php

namespace App\Service\Api;

use App\Utils\Api\RequestBlueprint;
use App\Utils\Api\ResourceData;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
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

    /**
     * Vytvori asynchronni request dle vzoru.
     *
     * @param RequestBlueprint $blueprint
     * @return PromiseInterface
     */
    private function createPromise(RequestBlueprint $blueprint): PromiseInterface
    {
        // vytvori objekt Guzzle requestu
        $request = $this->createRequest($blueprint);

        // callback v pripade, ze asynchronni request probehl v poradku
        $onFulfilled = function (Response $response) use ($blueprint) {
            $json = json_decode((string)$response->getBody(), true);
            if ($json === null) {
                throw new BadResponseException("Chybna data z API pro URL {$blueprint->getUri()}.");
            }

            $this->data->{$blueprint->getContainerName()} = $json;

            if ($blueprint->isCacheAvailable()) {
                $elapsedTime = microtime(true) - $blueprint->getRequestStartTimestamp();
                $this->cacheHelper->saveCacheItem($blueprint->getCacheKey(), $json, $blueprint->getCacheLifetime(), $elapsedTime);
            }
        };

        return $this->client->sendAsync($request)->then($onFulfilled);
    }

    /**
     * Vytvori request dle vzoru pro komunikaci s API.
     *
     * @param RequestBlueprint $blueprint
     * @return Request
     */
    private function createRequest(RequestBlueprint $blueprint): Request
    {
        $headers = $this->getCommonHeaders();

        if ($blueprint->getBodyData()) {
            $headers += [
                "Content-Type" => "application/json"
            ];

            return new Request(
                $blueprint->getMethod(),
                $blueprint->getUri(),
                $headers,
                json_encode($blueprint->getBodyData())
            );
        }

        $blueprint->setRequestStartTimestamp(microtime(true));

        return new Request($blueprint->getMethod(), $blueprint->getUri(), $headers);
    }

    private function getCommonHeaders(): array
    {
        $headers = [];

        if ($this->request) {
            $headers["X-User-Ip"] = $this->request->getClientIp();
            $headers["X-Request-Uri"] = $this->request->getSchemeAndHttpHost() . $this->request->getRequestUri();
            $headers["X-User-Agent"] = $this->request->headers->get("User-Agent");
        }

        $headers["X-Api-Key"] = $this->apiKey;
        $headers["X-App-Domain"] = self::X_APP_DOMAIN_HEADER;

        return $headers;
    }

    /**
     * Odesle asnychronni pozadavky reprezentovane polem objektu PromiseInterface.
     *
     * @param PromiseInterface[] $promises
     */
    private function send(array $promises): void
    {
        (new EachPromise($promises))->promise()->wait();
    }

    /**
     * Vrati vsechny rejected promises (neuspesne asynchronni reuqesty).
     *
     * @return PromiseInterface[]
     */
    private function getRejectedPromises(): array
    {
        $rejected = [];
        foreach ($this->promises as $promise) {
            if ($promise->getState() === PromiseInterface::REJECTED) {
                $this->checkStatusCode($promise);
                $rejected[] = $promise;
            }
        }
        return $rejected;
    }

    /**
     * Zkontroluje HTTP stavovy kod odpovedi a prislusne na nej pripadne zareaguje.
     *
     * @param PromiseInterface $promise
     */
    private function checkStatusCode(PromiseInterface $promise): void
    {
        /** @var \Throwable $throwable */
        $throwable = inspect($promise)["reason"];

        $statusCode = $throwable->getCode();

        if ($statusCode === SymfonyResponse::HTTP_NOT_FOUND) {
            // Pozadovany zdroj nebyl nalezen
            throw new NotFoundHttpException($throwable->getMessage());
        }
    }
}
