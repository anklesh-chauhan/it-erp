<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\HasApprovalWorkflow;

class OrganizationalUnit extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['name', 'code', 'description', 'parent_id', 'is_active'];

    // Parent OU (for hierarchy)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class, 'parent_id');
    }

    // Child OUs (for hierarchy)
    public function children(): HasMany
    {
        return $this->hasMany(OrganizationalUnit::class, 'parent_id');
    }

    // Users assigned to this OU
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Leads associated with this OU
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    // Deals associated with this OU
    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
