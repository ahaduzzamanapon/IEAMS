<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Land extends Model
{
    protected $fillable = [
        'project_id', 'purchase_date', 'purchase_value', 'seller_information',
        'deed_number', 'registration_date', 'khatian_number', 'dag_number',
        'land_amount', 'land_classification', 'land_map_path'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'registration_date' => 'date',
        'purchase_value' => 'decimal:2',
        'land_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Land $land) {
            $project = $land->project;
            if ($project) {
                // BR-01: Purchase Date cannot be after Project Start Date
                if ($land->purchase_date > $project->project_start_date) {
                    throw new \InvalidArgumentException("Purchase Date cannot be after the Project Start Date ({$project->project_start_date->format('Y-m-d')}).");
                }
                // BR-02: Registration Date must be on or after Purchase Date
                if ($land->registration_date < $land->purchase_date) {
                    throw new \InvalidArgumentException("Registration Date must be on or after the Purchase Date.");
                }
                // BR: Land Amount must not exceed Project Total Land
                $existingLand = Land::where('project_id', $land->project_id);
                if ($land->exists) {
                    $existingLand->where('id', '!=', $land->id);
                }
                $totalExistingLand = $existingLand->sum('land_amount');
                if (($totalExistingLand + $land->land_amount) > $project->total_land) {
                    throw new \InvalidArgumentException("Total registered land amount exceeds the Project's Total Land ({$project->total_land}).");
                }
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class);
    }
}
