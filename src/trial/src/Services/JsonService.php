<?php

namespace App\Services;

use App\Entity\Category;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class JsonService
{
    public DecoderInterface $decoder;

    public function __construct(DecoderInterface $decoder)
    {
        $this->decoder = $decoder;
    }

    public function getData(array $decodedProducts): array
    {
        $data = [];

        foreach ($decodedProducts as $product) {
            $productArray['onlinerId'] = $product['id'];
            $productArray['name'] = $product['full_name'];
            $productArray['linkToImage'] = $product['images']['header'];
            $productArray['description'] = $product['description'];
            $productArray['url'] = $product['html_url'];

            $prices = $this->getPriceByProduct($product);

            $data[] = ['product' => $productArray, 'price' => $prices];
        }

        return $data;
    }

    private function getPriceByProduct(array $product): array
    {
        $prices = $product['prices'];

        $minPrice = floatval($prices['price_min']['amount'] ?? 0);
        $maxPrice = floatval($prices['price_max']['amount'] ?? 0);

        $pricesForDenormalize['minPrice'] = $minPrice;
        $pricesForDenormalize['maxPrice'] = $maxPrice;
        $pricesForDenormalize['avgPrice'] = floatval(($minPrice + $maxPrice) / 2);
        $pricesForDenormalize['currency'] = $prices['price_min']['converted']['BYN']['currency'] ?? null;

        return $pricesForDenormalize;
    }
}