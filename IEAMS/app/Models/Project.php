<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'project_id_code', 'project_code', 'name', 'division', 'district', 'upazila',
        'mouza', 'total_land', 'total_road_land', 'estimated_project_cost',
        'total_planned_plot', 'total_planned_apartment', 'project_start_date',
        'expected_completion_date', 'status', 'description'
    ];

    protected $casts = [
        'total_land' => 'decimal:2',
        'total_road_land' => 'decimal:2',
        'estimated_project_cost' => 'decimal:2',
        'project_start_date' => 'date',
        'expected_completion_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Project $project) {
            if ($project->total_road_land >= $project->total_land) {
                throw new \InvalidArgumentException("Total Road Land cannot be greater than or equal to Total Land.");
            }
        });
    }

    public function lands(): HasMany
    {
        return $this->hasMany(Land::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class);
    }
}
