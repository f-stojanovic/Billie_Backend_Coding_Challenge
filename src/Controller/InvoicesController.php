<?php

namespace App\Controller;

use App\Form\InvoiceForm;
use App\Repository\InvoiceRepository;
use App\Service\InvoiceService;
use App\Service\MenuBuilder;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InvoicesController.
 */
class InvoicesController extends AbstractController
{
    /**
     * @var MenuBuilder
     */
    private $builder;

    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;

    /**
     * @var InvoiceService
     */
    private $invoiceService;

    /**
     * InvoiceController constructor.
     */
    public function __construct(
        MenuBuilder            $builder,
        InvoiceRepository      $invoiceRepository,
        InvoiceService         $invoiceService
    ) {
        $this->builder           = $builder;
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceService    = $invoiceService;
    }

    /**
     * @Route("/invoices", name="invoices")
     */
    public function getInvoicesAction(Request $request): Response
    {
        $page = intval($request->get('page'));
        if ($page < 1) {
            $page = 1;
        }

        $invoiceList = $this->invoiceRepository->getInvoiceListPaginated($page);

        return $this->render('invoice/invoices.html.twig', [
            'menu' => $this->builder->getMenuData(),
            'invoiceList' => $invoiceList['data'],
            'contentTitle' => 'Invoice List',
            'totalItems' => $invoiceList['totalItems'],
            'totalPages' => $invoiceList['pagesCount'],
            'pageSize' => 20,
            'currentPage' => $page,
        ]);
    }

    /**
     * @Route("/invoice/add", name="add_invoice")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function addInvoiceAction(Request $request): Response
    {
        $form = $this->createForm(InvoiceForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($this->invoiceService->checkDebtLimit($data->getDebtorCompanyId(), $data->getCost()))
            {
                $this->addFlash('danger', 'Invoices amount exceeds debt limit of the company!');
            } else {
                $this->invoiceService->addOrEditInvoice(
                    @$data->getId(),
                    $data->getDebtorCompanyId(),
                    $data->getCreditorCompanyId(),
                    $data->getService(),
                    $data->getQuantity(),
                    $data->getCost(),
                    $data->getStatusType(),
                );
                $this->addFlash('success', 'Invoice added!');
            }
            return $this->redirectToRoute('invoices');
        }
        return $this->render('invoice/add-edit-invoice.html.twig', [
            'menu' => $this->builder->getMenuData(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/invoice/edit/{id}", name="edit_invoice")
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function editInvoiceAction(int $id, Request $request): Response
    {
        $invoice = $this->invoiceRepository->find($id);

        $form = $this->createForm(InvoiceForm::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->invoiceService->addOrEditInvoice(
                $data->getId(),
                $data->getDebtorCompanyId(),
                $data->getCreditorCompanyId(),
                $data->getService(),
                $data->getQuantity(),
                $data->getCost(),
                $data->getStatusType(),
            );

            $this->addFlash('success', 'Invoice updated!');

            return $this->redirectToRoute('invoices');
        }
        return $this->render('invoice/add-edit-invoice.html.twig', [
            'invoice' => $invoice,
            'menu'    => $this->builder->getMenuData(),
            'form'    => $form->createView()
        ]);
    }

    /**
     * @Route("/invoice/delete/{id}", name="delete_invoice")
     */
    public function deleteInvoiceAction(int $id): Response
    {
        $invoice = $this->invoiceRepository->find($id);
        $this->invoiceRepository->deleteInvoice($invoice);

        $this->addFlash('success', 'Invoice deleted!');

        return $this->redirectToRoute('invoices');
    }
}