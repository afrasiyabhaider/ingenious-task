<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    /**
     * Get company data
     *
     **/
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }
    /**
     * Get billed to company data
     *
     **/
    public function billedToCompany()
    {
        return $this->belongsTo(Company::class,'billed_to_company_id','id');
    }
    /**
     * Get invoice products
     *
     **/
    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProductLine::class);
    }

    /**
     * Get invoice data
     *
     **/
    public function getInvoiceData($id)
    {
        $invoice = $this->find($id);
        $company = $invoice->company()->first();
        $billedToCompany = $invoice->billedToCompany()->first();
        $invoiceProducts = $invoice->invoiceProducts()->get();
        $invoiceData[] = $invoice;
        $totalProductAmount = 0;
        $products= [];
        foreach ($invoiceProducts as $key => $value) {
            $products[] = [
                'name' => $value->product->name,
                'quantity' => $value->quantity,
                'unit_price' => $value->product->price,
                'total' => $value->quantity*$value->product->price,
            ];
            $totalProductAmount += $value->quantity * $value->product->price;
        }
        return [
            'invoice_number' => $invoice->number,
            'invoice_status' => $invoice->status,
            'invoice_date' => $invoice->date,
            'due_date' => $invoice->due_date,
            'company' => [
                'name' => $company->name,
                'street' => $company->street,
                'city' => $company->city,
                'zip_code' => $company->zip_code,
                'phone' => $company->phone,
            ],
            'billedToCompany' => [
                'name' => $billedToCompany->name,
                'street' => $billedToCompany->street,
                'city' => $billedToCompany->city,
                'zip_code' => $billedToCompany->zip_code,
                'phone' => $billedToCompany->phone,
                'email_address' => $billedToCompany->email,
            ],
            'products' => $products,
            'total_price' => $totalProductAmount,
        ];
    }

    /**
     * Approve/Reject Invoice
     *
     **/
    public function changeStatus($id,$status)
    {
        $invoice = $this->find($id);
        if ($invoice->status != 'draft') {
            return ['message'=>'Can not change status, invoice is already '.$invoice->status];
        }
        $resp = $invoice->update([
            'status' => $status
        ]);
        if ($resp) {
            return ['message'=>'Invoice status updated successfully'];
        }else{
            return ['message'=>'Invoice status update failed'];
        }
    }
}
