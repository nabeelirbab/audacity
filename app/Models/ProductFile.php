<?php

namespace App\Models;

use App\Models\Product;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ProductFile extends Model
{
    protected $table = 'licensing_product_files';

    protected $fillable = ['name','path','product_id', 'type'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

}
