<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * CourseFile
 *
 * @mixin Builder
 */
class CourseFile extends Model
{
    use HasFactory;

    protected $table = 'course_files';
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_ID', 'id');
    }

    /*
     * Business logic
     */
    public function getFile(int $fileId) : ?CourseFile
    {
        return $this->find($fileId);
    }

    public function getCourse(int $fileId) : Course
    {
        return $this->getFile($fileId)->course;
    }

    public function getFileForCourse(int $courseId, string $filenameOriginal) : ?CourseFile
    {
        return $this->where(['course_ID' => $courseId, 'filename_original' => $filenameOriginal])
                    ->first();
    }
}
