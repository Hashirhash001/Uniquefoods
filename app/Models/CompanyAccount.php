<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CompanyAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'primary_email', 'phone', 'address', 'is_active'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name) . '-' . Str::random(4);
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_account_id', 'user_id')
                    ->withPivot('role', 'is_active')
                    ->withTimestamps();
    }

    public function activeUsers()
    {
        return $this->users()->wherePivot('is_active', true);
    }

    public function groups()
    {
        return $this->belongsToMany(CustomerGroup::class, 'company_group', 'company_account_id', 'customer_group_id')
                    ->withTimestamps();
    }

    public function owner()
    {
        return $this->users()->wherePivot('role', 'owner')->first();
    }
}
