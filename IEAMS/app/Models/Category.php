<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['asset_type', 'name', 'code'];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
