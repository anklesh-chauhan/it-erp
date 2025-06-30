<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\PositionStatus; // Import the new enum

class Position extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'positions'; // Explicitly define table name if it's not the plural of model name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'territory_id',
        'division_id',
        'department_id',
        'job_title_id',
        'job_grade_id',
        'reports_to_position_id',
        'description',
        'status',
        'location_id',
        'level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => PositionStatus::class, // Cast to the new PositionStatus enum
    ];

    /**
     * Get the territory associated with the position.
     */
    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    /**
     * Get the division associated with the position.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(EmpDivision::class, 'division_id');
    }

    /**
     * Get the department associated with the position.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }

    /**
     * Get the job title associated with the position.
     */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(EmpJobTitle::class, 'job_title_id');
    }

    /**
     * Get the job grade associated with the position.
     */
    public function jobGrade(): BelongsTo
    {
        return $this->belongsTo(EmpGrade::class, 'job_grade_id');
    }

    /**
     * Get the position this position reports to.
     */
    public function reportsTo(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'reports_to_position_id');
    }

    /**
     * Get the positions that report to this position.
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Position::class, 'reports_to_position_id');
    }

    /**
     * Get the location master associated with the position.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(LocationMaster::class, 'location_id');
    }

    /**
     * The organizational units that belong to the position.
     */
    public function organizationalUnits(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationalUnit::class, 'position_organizational_unit_pivot', 'position_id', 'organizational_unit_id')
                    ->withTimestamps(); // Assuming your pivot table has timestamps based on the other pivot
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_position_pivot', 'position_id', 'employee_id')
                    ->withTimestamps();
    }
}
