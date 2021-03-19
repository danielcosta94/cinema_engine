<?php

namespace App\Controller\Api;

use App\Service\ShoppingCartService;
use App\Service\VoucherService;
use App\Traits\JsonParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShoppingCartController
 *
 * @package App\Controller
 * @Route("/api/shopping_carts/", name="shopping_carts_api_")
 */
class ShoppingCartController extends AbstractController
{
    use JsonParser;

    /**
     * @param Request $request
     * @param $id
     * @param ShoppingCartService $shoppingCartService
     * @param VoucherService $voucherService
     * @return JsonResponse
     * @Route("{id}/voucher", name="add_voucher_to_shopping_cart", methods={"POST"})
     */
    public function applyVoucherDiscountToShoppingCart(Request $request, $id, ShoppingCartService $shoppingCartService, VoucherService $voucherService): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $shoppingCart = $shoppingCartService->findShoppingCartById($id);
            if ($shoppingCart) {
                $voucherCode = $request->request->get('code');

                if ($voucherCode != null) {
                    $voucher = $voucherService->findVoucherByCriteria(['code' => $voucherCode]);
                    if ($voucher != null) {
                        if ($shoppingCartService->addVoucherToShoppingCart($shoppingCart, $voucher)) {
                            $message = "The voucher was added to the shopping cart";
                            $responseCode = Response::HTTP_OK;
                        } else {
                            $message = "The voucher was not added to the shopping cart";
                            $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                        }
                    } else {
                        $message = "Voucher code does not exists";
                        $responseCode = Response::HTTP_NOT_FOUND;
                    }
                } else {
                    $message = "Voucher code was not given";
                    $responseCode = Response::HTTP_BAD_REQUEST;
                }
            } else {
                $message = "Shopping cart not exists";
                $responseCode = Response::HTTP_NOT_FOUND;
            }
        } catch (UnprocessableEntityHttpException $unprocessableEntityHttpException) {
            $message = $unprocessableEntityHttpException->getMessage();
            $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->response(['message' => $message], $responseCode);
    }

    /**
     * @param Request $request
     * @param $id
     * @param ShoppingCartService $shoppingCartService
     * @return JsonResponse
     * @Route("{id}/total", name="get_gross_total_shopping_cart", methods={"GET"})
     */
    public function getGrossTotalPriceOfShoppingCart(Request $request, $id, ShoppingCartService $shoppingCartService): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $shoppingCart = $shoppingCartService->findShoppingCartById($id);
            if ($shoppingCart) {
                $priceGrossTotal = $shoppingCartService->getGrossTotalPriceOfShoppingCart($shoppingCart);
                return $this->response(['price_gross_total' => $priceGrossTotal]);
            } else {
                $message = "Shopping cart not exists";
                $responseCode = Response::HTTP_NOT_FOUND;
            }
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->response(['message' => $message], $responseCode);
    }
}