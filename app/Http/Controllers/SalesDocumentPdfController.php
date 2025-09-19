<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SalesDocumentPdfController extends Controller
{
    public function preview(string $type, int $id)
    {
        $document = $this->getModel($type)::with(['items.itemMaster', 'contactDetail',  'taxDetails'])->findOrFail($id);
        
        $organization = \App\Models\Organization::with('addresses')->first();
        
        $pdf = Pdf::loadView('pdf.sales-document', compact('document', 'organization'));

        return $pdf->stream(strtolower($type) . '-' . $document->document_number . '.pdf');
    }

    public function download(string $type, int $id)
    {
        $document = $this->getModel($type)::with(['items.itemMaster', 'contactDetail'])->findOrFail($id);
        
        $organization = \App\Models\Organization::first();
        
        $pdf = Pdf::loadView('pdf.sales-document', compact('document', 'organization'));

        return $pdf->download(strtolower($type) . '-' . $document->document_number . '.pdf');
    }

    private function getModel(string $type)
    {
        return match (strtolower($type)) {
            'quote' => \App\Models\Quote::class,
            'order' => \App\Models\SalesOrder::class,
            'invoice' => \App\Models\SalesInvoice::class,
            default => abort(404, 'Invalid document type'),
        };
    }
}
