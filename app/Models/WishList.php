<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WishList extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "wishlists";

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}