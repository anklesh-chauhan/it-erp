<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Traits\HasApprovalWorkflow;

class Employee extends Model
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id', 'first_name', 'middle_name', 'last_name', 'email', 'mobile_number',
        'date_of_birth', 'gender', 'country_id', 'marital_status', 'phone_number',
        'emergency_contact_name', 'emergency_contact_number', 'age', 'contact_details',
        'profile_picture', 'blood_group', 'is_active', 'login_id', 'created_by_user_id',
        'updated_by_user_id', 'deleted_by_user_id', 'is_deleted'
    ];

    protected $casts = [
        'gender' => 'string',
        'marital_status' => 'string',
        'blood_group' => 'string',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'date_of_birth' => 'date',
    ];

    protected $appends = ['full_name'];

    public function attendance()
    {
        return $this->hasMany(EmployeeAttendance::class);
    }

    public function bankDetail()
    {
        return $this->morphMany(BankDetail::class, 'bankable');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? now()->diffInYears($this->date_of_birth) : null;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'login_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by_user_id');
    }

    public function employmentDetail()
    {
        return $this->hasOne(EmploymentDetail::class, 'employee_id');
    }

    public function professionalTax()
    {
        return $this->hasMany(EmpProfessionalTax::class, 'employee_id');
    }

    public function statutoryIds()
    {
        return $this->hasOne(EmpStatutoryId::class, 'employee_id');
    }

    public function qualifications()
    {
        return $this->hasMany(EmpQualification::class, 'employee_id');
    }

    public function skills()
    {
        return $this->hasMany(EmpSkill::class, 'employee_id');
    }

    public function departments()
    {
        return $this->hasMany(EmpDepartment::class, 'department_head_id');
    }

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'employee_position_pivot', 'employee_id', 'position_id')
                    ->withTimestamps();
    }

}
