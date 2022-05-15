<?php

namespace App\Controller;

use App\Repository\InvoiceRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;

    /**
     * PdfController constructor.
     */
    public function __construct(
        InvoiceRepository $invoiceRepository,
    )
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @Route("/download-invoice/{id}", name="invoice_download")
     */
    public function generatePdf($id)
    {
        $invoice = $this->invoiceRepository->find($id);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('invoice_pdf.html.twig', [
            'invoiceNo'       => $invoice->getInvoiceNo(),
            'creditorAddress' => $invoice->getCreditorCompanyId()->getCompanyName(),
            'debtorAddress'   => $invoice->getDebtorCompanyId()->getCompanyName(),
            'debtorEmail'     => $invoice->getDebtorCompanyId()->getEmail(),
            'createdAt'       => $invoice->getCreatedAt(),
            'service'         => $invoice->getService(),
            'quantity'        => $invoice->getQuantity(),
            'cost'            => $invoice->getCost(),
            'amount'          => $this->invoiceRepository->getInvoicesAmountForCompany($invoice->getDebtorCompanyId())
            ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        ob_get_clean();
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("invoice.pdf", [
            "Attachment" => false
        ]);
    }
}