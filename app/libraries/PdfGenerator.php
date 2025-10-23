<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

require_once APP_DIR . '../vendor/tecnickcom/tcpdf/tcpdf.php';

class PdfGenerator {

    private $pdf;

    public function __construct() {
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $this->pdf->SetCreator('Barangay Management System');
        $this->pdf->SetAuthor('Barangay Management System');
        $this->pdf->SetTitle('Official Document');

        // Set default header data
        $this->pdf->SetHeaderData('', 0, 'BARANGAY MANAGEMENT SYSTEM', 'Official Document');

        // Set header and footer fonts
        $this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set default monospaced font
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    }

    public function generate_document_certificate($document) {
        $this->pdf->AddPage();

        // Title
        $this->pdf->SetFont('helvetica', 'B', 16);
        $this->pdf->Cell(0, 10, 'OFFICIAL DOCUMENT CERTIFICATE', 0, 1, 'C');
        $this->pdf->Ln(10);

        // Document details
        $this->pdf->SetFont('helvetica', '', 12);
        $this->pdf->Cell(50, 10, 'Document Type:', 0, 0);
        $this->pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $document['document_type'])), 0, 1);

        $this->pdf->Cell(50, 10, 'Purpose:', 0, 0);
        $this->pdf->Cell(0, 10, $document['purpose'], 0, 1);

        $this->pdf->Cell(50, 10, 'Requested By:', 0, 0);
        $this->pdf->Cell(0, 10, isset($document['citizen_name']) ? $document['citizen_name'] : $document['username'], 0, 1);

        $this->pdf->Cell(50, 10, 'Date Requested:', 0, 0);
        $this->pdf->Cell(0, 10, date('F d, Y', strtotime($document['requested_at'])), 0, 1);

        $this->pdf->Cell(50, 10, 'Date Approved:', 0, 0);
        $this->pdf->Cell(0, 10, date('F d, Y'), 0, 1);

        $this->pdf->Ln(20);

        // Approval statement
        $this->pdf->SetFont('helvetica', 'I', 12);
        $this->pdf->MultiCell(0, 10, 'This document has been officially approved and processed by the Barangay Management System. This certificate serves as proof of the document request and approval.', 0, 'L');

        $this->pdf->Ln(20);

        // Signature area
        $this->pdf->SetFont('helvetica', '', 12);
        $this->pdf->Cell(80, 10, 'Approved By:', 0, 0);
        $this->pdf->Cell(0, 10, 'Barangay Official', 0, 1);

        $this->pdf->Ln(20);
        $this->pdf->Cell(80, 10, 'Date:', 0, 0);
        $this->pdf->Cell(0, 10, date('F d, Y'), 0, 1);

        return $this->pdf->Output('document_certificate_' . $document['id'] . '.pdf', 'S');
    }

    public function generate_permit_certificate($permit) {
        $this->pdf->AddPage();

        // Title
        $this->pdf->SetFont('helvetica', 'B', 16);
        $this->pdf->Cell(0, 10, 'BUSINESS PERMIT CERTIFICATE', 0, 1, 'C');
        $this->pdf->Ln(10);

        // Permit details
        $this->pdf->SetFont('helvetica', '', 12);
        $this->pdf->Cell(50, 10, 'Business Name:', 0, 0);
        $this->pdf->Cell(0, 10, $permit['business_name'], 0, 1);

        $this->pdf->Cell(50, 10, 'Business Address:', 0, 0);
        $this->pdf->Cell(0, 10, $permit['business_address'], 0, 1);

        $this->pdf->Cell(50, 10, 'Owner:', 0, 0);
        $this->pdf->Cell(0, 10, $permit['owner_name'] ?? $permit['username'], 0, 1);

        $this->pdf->Cell(50, 10, 'Date Applied:', 0, 0);
        $this->pdf->Cell(0, 10, date('F d, Y', strtotime($permit['applied_at'])), 0, 1);

        $this->pdf->Cell(50, 10, 'Date Approved:', 0, 0);
        $this->pdf->Cell(0, 10, date('F d, Y'), 0, 1);

        $this->pdf->Ln(20);

        // Approval statement
        $this->pdf->SetFont('helvetica', 'I', 12);
        $this->pdf->MultiCell(0, 10, 'This business permit has been officially approved and issued by the Barangay Management System. The permit holder is authorized to operate the specified business within the barangay jurisdiction.', 0, 'L');

        $this->pdf->Ln(20);

        // Validity
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->Cell(0, 10, 'Valid Until: ' . date('F d, Y', strtotime('+1 year')), 0, 1);

        $this->pdf->Ln(20);

        // Signature area
        $this->pdf->SetFont('helvetica', '', 12);
        $this->pdf->Cell(80, 10, 'Approved By:', 0, 0);
        $this->pdf->Cell(0, 10, 'Barangay Official', 0, 1);

        $this->pdf->Ln(20);
        $this->pdf->Cell(80, 10, 'Date:', 0, 0);
        $this->pdf->Cell(0, 10, date('F d, Y'), 0, 1);

        return $this->pdf->Output('business_permit_' . $permit['id'] . '.pdf', 'S');
    }

    public function generate_payment_receipt($payment_data) {
        $this->pdf->AddPage();

        // Title
        $this->pdf->SetFont('helvetica', 'B', 16);
        $title = ($payment_data['payment_type'] ?? 'online') === 'cash_on_pickup' ? 'PAYMENT RECEIPT (CASH ON PICKUP)' : 'PAYMENT RECEIPT';
        $this->pdf->Cell(0, 10, $title, 0, 1, 'C');
        $this->pdf->Ln(10);

        // Receipt details
        $this->pdf->SetFont('helvetica', '', 12);
        $this->pdf->Cell(50, 10, 'Receipt No:', 0, 0);
        $this->pdf->Cell(0, 10, $payment_data['receipt_no'] ?? 'RCP-' . time(), 0, 1);

        $this->pdf->Cell(50, 10, 'Date:', 0, 0);
        $this->pdf->Cell(0, 10, date('F d, Y'), 0, 1);

        $this->pdf->Cell(50, 10, 'Paid By:', 0, 0);
        $this->pdf->Cell(0, 10, $payment_data['paid_by'], 0, 1);

        $this->pdf->Cell(50, 10, 'Amount:', 0, 0);
        $this->pdf->Cell(0, 10, 'PHP ' . number_format($payment_data['amount'], 2), 0, 1);

        $this->pdf->Cell(50, 10, 'Payment For:', 0, 0);
        $this->pdf->Cell(0, 10, $payment_data['description'], 0, 1);

        $this->pdf->Cell(50, 10, 'Payment Type:', 0, 0);
        $this->pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $payment_data['payment_type'] ?? 'online')), 0, 1);

        $this->pdf->Ln(20);

        // Payment details
        $this->pdf->SetFont('helvetica', 'I', 12);
        if (($payment_data['payment_type'] ?? 'online') === 'cash_on_pickup') {
            $this->pdf->MultiCell(0, 10, 'Cash payment selected. Please present this receipt at the barangay hall to pay and collect your document.', 0, 'L');
        } else {
            $this->pdf->MultiCell(0, 10, 'Payment has been received and processed. Please present this receipt at the barangay hall for confirmation and document pickup.', 0, 'L');
        }

        $this->pdf->Ln(20);

        // Signature area
        $this->pdf->SetFont('helvetica', '', 12);
        $this->pdf->Cell(80, 10, 'Received By:', 0, 0);
        $this->pdf->Cell(0, 10, 'Barangay Treasurer', 0, 1);

        $this->pdf->Ln(20);
        $this->pdf->Cell(80, 10, 'Date:', 0, 0);
        $this->pdf->Cell(0, 10, date('F d, Y'), 0, 1);

        return $this->pdf->Output('payment_receipt_' . ($payment_data['receipt_no'] ?? time()) . '.pdf', 'S');
    }

    public function output_pdf($filename = 'document.pdf') {
        $this->pdf->Output($filename, 'I');
    }

    public function get_pdf_content() {
        return $this->pdf->Output('', 'S');
    }
}
