<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Report
 *
 * @property int $id
 * @property string $ticket_no
 * @property string|null $queue_no
 * @property string $title
 * @property string $description
 * @property string $category
 * @property string $status
 * @property string $priority
 * @property int $user_id
 * @property int|null $department_id
 * @property int|null $assigned_to
 * @property string|null $location
 * @property array|null $attachments
 * @property string|null $resolution_notes
 * @property string|null $completion_notes
 * @property string|null $final_notes
 * @property \Carbon\Carbon|null $resolved_at
 * @property \Carbon\Carbon|null $sla_due_at
 * @property bool $is_escalated
 * @property int $reassign_count
 * @property \Carbon\Carbon|null $last_activity_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Department|null $department
 * @property-read \App\Models\User|null $assignedUser
 */
class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_no',
        'queue_no',
        'title',
        'description',
        'category',
        'status',
        'priority',
        'user_id',
        'department_id',
        'assigned_to',
        'location',
        'attachments',
        'resolution_notes',
        'completion_notes',
        'final_notes',
        'rejection_reason',
        'resolved_at',
        'sla_due_at',
        'is_escalated',
        'reassign_count',
        'last_activity_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
        'sla_due_at' => 'datetime',
        'is_escalated' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (empty($report->ticket_no)) {
                // Generate ticket number with date prefix for better tracking
                $datePrefix = now()->format('Ymd');
                $randomSuffix = strtoupper(Str::random(6));
                $report->ticket_no = 'RPT-'.$datePrefix.'-'.$randomSuffix;
            }

            // Calculate SLA due date based on priority if not explicitly set
            if (empty($report->sla_due_at)) {
                $report->sla_due_at = $report->calculateSLADueDate();
            }
        });

        static::updating(function ($report) {
            $report->last_activity_at = now();

            // Automatically recalculate SLA due date when priority changes
            if ($report->isDirty('priority') && ! $report->isDirty('sla_due_at')) {
                $report->sla_due_at = $report->calculateSLADueDate();
            }
        });
    }

    /**
     * Generate next queue number for today, format: Q-YYYYMMDD-####
     */
    public static function nextQueueNo(): string
    {
        $date = now()->format('Ymd');
        $prefix = 'Q-'.$date.'-';

        $latest = static::where('queue_no', 'like', $prefix.'%')
            ->orderBy('queue_no', 'desc')
            ->lockForUpdate()
            ->value('queue_no');

        if ($latest) {
            $lastSeq = (int) substr($latest, strlen($prefix));
            $seq = str_pad((string) ($lastSeq + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $seq = '0001';
        }

        return $prefix.$seq;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignments()
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function calculateSLADueDate()
    {
        $slaHours = [
            'urgent' => 2,
            'high' => 8,
            'medium' => 24,
            'low' => 72,
        ];

        $hours = $slaHours[$this->priority] ?? 24;

        return now()->addHours($hours);
    }

    public function isSLABreached()
    {
        return $this->sla_due_at && now()->isAfter($this->sla_due_at);
    }

    public function canBeReopened()
    {
        return in_array($this->status, ['closed', 'resolved']) &&
               ($this->resolved_at === null || $this->resolved_at->diffInDays(now()) <= 30);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'verified']);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['assigned', 'in_progress', 'awaiting_info']);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEscalated($query)
    {
        return $query->where('is_escalated', true);
    }
}
