<?php

namespace App\Controller;

use App\Entity\TaxRate;
use App\Form\TaxRateType;
use App\Repository\TaxRateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tax_rates")
 */
class TaxRateController extends AbstractController
{
    /**
     * @Route("/", name="tax_rate_index", methods={"GET"})
     */
    public function index(TaxRateRepository $taxRateRepository): Response
    {
        return $this->render('tax_rate/index.html.twig', [
            'tax_rates' => $taxRateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tax_rate_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $taxRate = new TaxRate();
        $form = $this->createForm(TaxRateType::class, $taxRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taxRate);
            $entityManager->flush();

            return $this->redirectToRoute('tax_rate_index');
        }

        return $this->render('tax_rate/new.html.twig', [
            'tax_rate' => $taxRate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tax_rate_show", methods={"GET"})
     */
    public function show(TaxRate $taxRate): Response
    {
        return $this->render('tax_rate/show.html.twig', [
            'tax_rate' => $taxRate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tax_rate_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TaxRate $taxRate): Response
    {
        $form = $this->createForm(TaxRateType::class, $taxRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tax_rate_index');
        }

        return $this->render('tax_rate/edit.html.twig', [
            'tax_rate' => $taxRate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tax_rate_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TaxRate $taxRate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taxRate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taxRate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tax_rate_index');
    }
}
