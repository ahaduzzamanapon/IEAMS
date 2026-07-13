<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = ['name', 'address', 'contact_person', 'mobile', 'email'];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}