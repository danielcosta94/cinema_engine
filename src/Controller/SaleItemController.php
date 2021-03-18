<?php

namespace App\Controller;

use App\Entity\SaleItem;
use App\Form\SaleItemType;
use App\Repository\SaleItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sale_items")
 */
class SaleItemController extends AbstractController
{
    /**
     * @Route("/", name="sale_item_index", methods={"GET"})
     */
    public function index(SaleItemRepository $saleItemRepository): Response
    {
        return $this->render('sale_item/index.html.twig', [
            'sale_items' => $saleItemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sale_item_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $saleItem = new SaleItem();
        $form = $this->createForm(SaleItemType::class, $saleItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($saleItem);
            $entityManager->flush();

            return $this->redirectToRoute('sale_item_index');
        }

        return $this->render('sale_item/new.html.twig', [
            'sale_item' => $saleItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sale_item_show", methods={"GET"})
     * @param SaleItem $saleItem
     * @return Response
     */
    public function show(SaleItem $saleItem): Response
    {
        return $this->render('sale_item/show.html.twig', [
            'sale_item' => $saleItem,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sale_item_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SaleItem $saleItem): Response
    {
        $form = $this->createForm(SaleItemType::class, $saleItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sale_item_index');
        }

        return $this->render('sale_item/edit.html.twig', [
            'sale_item' => $saleItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sale_item_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SaleItem $saleItem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$saleItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($saleItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sale_item_index');
    }
}
