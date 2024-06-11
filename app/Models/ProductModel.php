<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $fillable = ['name', 'brand_id'];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function items() {
        return $this->hasMany(Item::class, 'model_id');
    }
}
