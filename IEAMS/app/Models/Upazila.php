<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'district_id', 'name', 'bn_name', 'url'];

    public $incrementing = false; // IDs are seeded from raw source

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
