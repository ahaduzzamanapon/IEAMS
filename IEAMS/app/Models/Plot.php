<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plot extends Model
{
    protected $fillable = ['project_id', 'land_id', 'plot_number', 'plot_name', 'plot_area', 'status'];

    protected $casts = [
        'plot_area' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Plot $plot) {
            $project = $plot->project;
            if ($project) {
                // BR-01: Cannot create more plots than Project's total planned plots
                $plotsQuery = Plot::where('project_id', $plot->project_id);
                if ($plot->exists) {
                    $plotsQuery->where('id', '!=', $plot->id);
                }
                if ($plotsQuery->count() >= $project->total_planned_plot) {
                    throw new \InvalidArgumentException("Cannot add more plots. Total planned plots limit ({$project->total_planned_plot}) reached.");
                }

                // BR-02: Total plot area must not exceed project's total land (converted to Sq.Ft. from Acres)
                $projectLandSqFt = $project->total_land * 43560;
                $totalPlotArea = $plotsQuery->sum('plot_area');
                if (($totalPlotArea + $plot->plot_area) > $projectLandSqFt) {
                    throw new \InvalidArgumentException("Total plot area exceeds the Project's total land ({$projectLandSqFt} Sq.Ft.).");
                }

                // BR-03: Plot Number must be unique within the Project
                $numQuery = Plot::where('project_id', $plot->project_id)
                    ->where('plot_number', $plot->plot_number);
                if ($plot->exists) {
                    $numQuery->where('id', '!=', $plot->id);
                }
                if ($numQuery->exists()) {
                    throw new \InvalidArgumentException("Plot Number '{$plot->plot_number}' already exists in this Project.");
                }

                // BR-04: Plot Area must be greater than 0
                if ($plot->plot_area <= 0) {
                    throw new \InvalidArgumentException("Plot Area must be greater than 0.");
                }
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }
}
