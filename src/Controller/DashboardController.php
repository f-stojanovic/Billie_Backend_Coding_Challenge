<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\InvoiceRepository;
use App\Service\MenuBuilder;
use App\Traits\EntityManagerTrait;
use App\Traits\TwigTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    use EntityManagerTrait;
    use TwigTrait;

    /**
     * @var MenuBuilder
     */
    private MenuBuilder $builder;

    /**
     * @var CompanyRepository
     */
    private CompanyRepository $companyRepository;

    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoiceRepository;

    /**
     * DashboardController constructor.
     */
    public function __construct(
        MenuBuilder $builder,
        CompanyRepository $companyRepository,
        InvoiceRepository $invoiceRepository
    ) {
        $this->builder           = $builder;
        $this->companyRepository = $companyRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @Route("/dashboard", methods={"GET"}, name="dashboard")
     */
    public function dashboardAction(): Response
    {
        $menu          = $this->builder->getMenuData();
        $companies     = $this->companyRepository->getCompaniesCount();
        $invoices      = $this->invoiceRepository->getInvoicesCount();
        $invoicesList  = $this->invoiceRepository->getInvoicesForDashboard();

        return new Response($this->twig->render('index.html.twig', [
            'menu'         => $menu,
            'companies'    => $companies,
            'invoices'     => $invoices,
            'invoicesList' => $invoicesList
        ]));
    }
}


