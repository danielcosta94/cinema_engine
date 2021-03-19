<?php

namespace App\Controller;

use App\Entity\ShoppingCart;
use App\Repository\ShoppingCartRepository;
use App\Service\ShoppingCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shopping_carts")
 */
class ShoppingCartController extends AbstractController
{
    /**
     * @Route("/", name="shopping_cart_index", methods={"GET"})
     * @param ShoppingCartRepository $shoppingCartRepository
     * @return Response
     */
    public function index(ShoppingCartRepository $shoppingCartRepository): Response
    {
        return $this->render('shopping_cart/index.html.twig', [
            'shopping_carts' => $shoppingCartRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/invoice", name="shopping_cart_invoice_details", methods={"GET"})
     * @param ShoppingCart $shoppingCart
     * @param ShoppingCartService $shoppingCartService
     * @return Response
     * @throws \Exception
     */
    public function invoice(ShoppingCart $shoppingCart, ShoppingCartService $shoppingCartService): Response
    {
        $invoiceDetails = $shoppingCartService->getShoppingCartInvoiceDetails($shoppingCart);

        return $this->render('shopping_cart/invoice.html.twig', [
            'invoice_details' => $invoiceDetails,
        ]);
    }
}
