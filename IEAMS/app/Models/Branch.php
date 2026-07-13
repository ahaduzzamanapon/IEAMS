<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    protected $fillable = ['office_id', 'name', 'code'];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
