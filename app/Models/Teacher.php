<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Teacher
 *
 * @mixin Builder
 */
class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teacher_info';
    protected $primaryKey = 'user_ID';
    public $timestamps = false;
    protected $hidden = ['pivot'];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_ID', 'id');
    }

    /*
     * Business logic
     */
    public function getTeacherByUserId(int $userId): ?Teacher
    {
        return $this->where('user_ID', $userId)->first();
    }
}
