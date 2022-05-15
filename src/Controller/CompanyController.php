<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyForm;
use App\Repository\CompanyRepository;
use App\Service\CompanyService;
use App\Service\MenuBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompanyController.
 */
class CompanyController extends AbstractController
{
    /**
     * @var MenuBuilder
     */
    private $builder;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var CompanyService
     */
    private $companyService;

    /**
     * CompanyController constructor.
     */
    public function __construct(
        MenuBuilder            $builder,
        CompanyRepository      $companyRepository,
        CompanyService         $companyService
    )
    {
        $this->builder           = $builder;
        $this->companyRepository = $companyRepository;
        $this->companyService    = $companyService;
    }

    /**
     * @Route("/companies", name="companies")
     */
    public function getCompaniesAction(Request $request): Response
    {
        $page = intval($request->get('page'));
        if ($page < 1) {
            $page = 1;
        }

        $companyList = $this->companyRepository->getCompanyListPaginated($page);

        return $this->render('company/companies.html.twig', [
            'menu' => $this->builder->getMenuData(),
            'companyList' => $companyList['data'],
            'contentTitle' => 'Company List',
            'totalItems' => $companyList['totalItems'],
            'totalPages' => $companyList['pagesCount'],
            'pageSize' => 20,
            'currentPage' => $page,
        ]);
    }

    /**
     * @Route("/company/add", name="add_company")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addCompanyAction(Request $request): Response
    {
        $form = $this->createForm(CompanyForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->companyService->addOrEditCompany(
                @$data->getId(),
                $data->getCompanyName(),
                $data->getEmail(),
                $data->getAddress(),
                $data->getPhone(),
                $data->getCustomerType(),
                $data->getDebtLimit(),
            );

            $this->addFlash('success', 'Company added!');

            return $this->redirectToRoute('companies');
        }
        return $this->render('company/add-edit-company.html.twig', [
            'menu' => $this->builder->getMenuData(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/company/edit/{id}", name="edit_company")
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editCompanyAction(int $id, Request $request): Response
    {
        $company = $this->companyRepository->find($id);

        $form = $this->createForm(CompanyForm::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->companyService->addOrEditCompany(
                $id,
                $data->getCompanyName(),
                $data->getEmail(),
                $data->getAddress(),
                $data->getPhone(),
                $data->getCustomerType(),
                $data->getDebtLimit(),
            );

            $this->addFlash('success', 'Company updated!');

            return $this->redirectToRoute('companies');
        }
        return $this->render('company/add-edit-company.html.twig', [
            'company' => $company,
            'menu' => $this->builder->getMenuData(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/company/delete/{id}", name="delete_company")
     */
    public function deleteCompanyAction(int $id): Response
    {
        $company = $this->companyRepository->find($id);
        $this->companyRepository->deleteCompany($company);

        $this->addFlash('success', 'Company deleted!');

        return $this->redirectToRoute('companies');
    }
}