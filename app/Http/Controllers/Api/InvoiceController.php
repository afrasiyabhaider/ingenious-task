<?php

namespace App\Http\Controllers\Api;

use App\Http\Controller;
use App\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Get invoice data
     *
     **/
    public function getInvoice($id)
    {
        $invoice = new Invoice();
        if (!$invoice->find($id)) {
            return ['message'=>'Invalid invoice id'];
        }
        return $invoice->getInvoiceData($id);
    }
    /**
     * Invoice status change
     *
     **/
    public function changeStatus($id,$status)
    {
        $invoice = new Invoice();
        $status = strtolower($status);
        if (!$invoice->find($id)) {
            return ['message'=>'Invalid invoice id'];
        }
        if ($status != 'approved' && $status != 'rejected') {
            return ['message'=>'Invalid invoice status, approved/rejected is allowed'];
        }
        return $invoice->changeStatus($id,$status);
    }
}
