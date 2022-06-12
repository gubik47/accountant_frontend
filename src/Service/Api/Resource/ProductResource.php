<?php

namespace App\Service\CncAdminApi\Resource;

use App\Utils\Api\ResourceData;

class ProductResource extends Resource
{
    public function getProductDetailData(array $options): ResourceData
    {
        $this->blueprints[] = $this->factory->createProductDetailRequest($options["id"]);
        $this->blueprints[] = $this->factory->createProductOffersRequest($options);

        return $this->sendRequests();
    }

    public function getProductListData(array $options): ResourceData
    {
        $this->blueprints[] = $this->factory->createProductsRequest($options);
        $this->blueprints[] = $this->factory->createProductFiltersRequest($options);

        return $this->sendRequests();
    }

    public function getProducts(array $options): ResourceData
    {
        $this->blueprints[] = $this->factory->createProductsRequest($options);

        return $this->sendRequests();
    }
}