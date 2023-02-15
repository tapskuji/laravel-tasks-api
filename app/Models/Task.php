<?php

namespace App\Models;

use App\Models\Scopes\MostRecentScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property string title
 * @property string description
 * @property int completed
 * @property string due_date
 * @property string created_at
 * @property string updated_at
 */
class Task extends Model
{
    use HasFactory;

    const COMPLETED_TASK = '1';
    const INCOMPLETE_TASK = '0';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
        'due_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function getDueDateAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    public function getUpdatedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new MostRecentScope());
    }

    public function scopeFilter($query, array $array)
    {
        $filter = $array['search'] ?? null;
        $sortBy = $array['sort'] ?? null;
        $direction = $array['direction'] ?? 'desc';

        $query->when($filter, function ($query) use ($filter) {
            $query->where('title', 'LIKE', '%' . $filter . '%')
                ->orWhere('description', 'LIKE', '%' . $filter . '%');
        });

        $query->when($sortBy, function ($query) use ($sortBy, $direction) {
            $query->orderBy($sortBy, $direction);
        });

        return $query;
    }
}
