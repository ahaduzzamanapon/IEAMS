<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'bn_name', 'url'];

    public $incrementing = false; // IDs are seeded from raw source

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
