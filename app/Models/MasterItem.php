<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode', 'nama', 'harga_beli', 'laba', 'supplier', 'jenis', 'foto'
    ];

    public function categories()
    {
        return $this->belongsToMany(categories::class, 'category_master_items', 'master_item_id', 'category_id');
    }
}
