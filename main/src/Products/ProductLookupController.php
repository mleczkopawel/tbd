<?php

namespace Tbd\Main\Products;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
class ProductLookupController
{
    private $repository;

    private $dataProvider;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->dataProvider =  new ProductLookupDataProviderAbstraction;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        $product = $this->repository->findProduct($id);

        if ($product === null) {
            return Response::plaintext(
                "Product not found\n"
            )->withStatus(Response::STATUS_NOT_FOUND);
        }

        $data = $this->getDataProvider()->getData($product);

        return Response::json($data);
    }

    public function getDataProvider(): ProductLookupDataProviderAbstraction
    {
        return $this->dataProvider;
    }
}