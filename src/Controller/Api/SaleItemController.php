<?php

namespace App\Controller\Api;

use App\Service\SaleItemService;
use App\Traits\JsonParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SaleItemController
 *
 * @package App\Controller
 * @Route("/api/sale_items/", name="sale_items_api_")
 */
class SaleItemController extends AbstractController
{
    use JsonParser;

    /**
     * @param $barcode
     * @param SaleItemService $saleItemService
     * @return JsonResponse
     * @Route("barcode/{barcode}", name="sale_items_get_by_barcode", methods={"GET"})
     */
    public function getSaleItemByBarcode($barcode, SaleItemService $saleItemService): JsonResponse
    {
        try {
            $saleItem = $saleItemService->findSaleItems(['barcode' => $barcode]);
            if ($saleItem) {
                return $this->response($saleItem);
            }
            $message = "Sale item does not exists";
            $responseCode = Response::HTTP_NOT_FOUND;
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->response(['message' => $message], $responseCode);
    }

}