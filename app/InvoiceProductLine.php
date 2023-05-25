<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProductLine extends Model
{
    use HasFactory;
    protected $table = 'invoice_product_lines';
    /**
     * Get billed to company data
     *
     **/
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
