<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ActivityLog
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property array|null $request
 * @property array|null $properties
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ActivityLog newModelQuery()
 * @method static Builder|ActivityLog newQuery()
 * @method static Builder|ActivityLog query()
 * @method static Builder|ActivityLog whereCauserId($value)
 * @method static Builder|ActivityLog whereCauserType($value)
 * @method static Builder|ActivityLog whereCreatedAt($value)
 * @method static Builder|ActivityLog whereDescription($value)
 * @method static Builder|ActivityLog whereId($value)
 * @method static Builder|ActivityLog whereLogName($value)
 * @method static Builder|ActivityLog whereProperties($value)
 * @method static Builder|ActivityLog whereRequest($value)
 * @method static Builder|ActivityLog whereSubjectId($value)
 * @method static Builder|ActivityLog whereSubjectType($value)
 * @method static Builder|ActivityLog whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_name', 'description', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'request', 'properties'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'request' => 'json',
        'properties' => 'json'
    ];
}
