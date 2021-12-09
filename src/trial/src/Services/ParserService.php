<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

class ParserService
{
    const MAX_PRODUCTS_ON_PAGE = 36;

    private Client $guzzleClient;

    private array $allProducts = [];

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    public function parseAllProducts(string $category): array
    {
        $linkTemplate = 'https://catalog.onliner.by/sdapi/catalog.api/search/%s?page=%d&limit=%d';
        $json = $this->getJsonBody($linkTemplate, $category, 1);

        $this->mergeProducts($json['products']);

//        $amountPage = $json['page']['last'];
//
//        for ($i = 2; $i <= 2; $i++) {
//            $json = $this->getJsonBody($linkTemplate, $category, $i);
//
//            if (isset($json['products'])) {
//                $this->mergeProducts($json['products']);
//            }
//        }

        return $this->allProducts;
    }

    private function getJsonBody(string $linkTemplate, string $category, int $pageNumber): ?array
    {
        $link = sprintf($linkTemplate, $category, $pageNumber, self::MAX_PRODUCTS_ON_PAGE);
        $contents = $this->getContentsBody($link);

        return json_decode($contents, true);
    }

    private function getContentsBody(string $link): string
    {
        try {
            $response = $this->guzzleClient->get($link);
        } catch (GuzzleException $e) {
            throw new \Exception('Wrong link');
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return '';
        }

        return $response->getBody()->getContents();
    }

    private function mergeProducts(array $products): void
    {
        $this->allProducts = array_merge($this->allProducts, $products);
    }
}