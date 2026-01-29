<?php

namespace App\Models;

use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkAssignment extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $fillable = [
        'assignment_no',
        'assignment_year',
        'date',
        'ref_no',
        'ref_year',
        'customer_id',
        'customer_location_id',
        'ref_po_no_instruction',
        'scope',
        'estimation',
        'mobilization',
        'auth_name',
        'auth_pos',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerLocation(): BelongsTo
    {
        return $this->belongsTo(CustomerLocation::class);
    }

    public function workers(): HasMany
    {
        return $this->hasMany(WorkAssignmentWorker::class);
    }

    /**
     * Define searchable columns (direct)
     */
    protected function searchableColumns(): array
    {
        return ['assignment_no', 'ref_no'];
    }

    /**
     * Enhanced search scope with relationship support
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('assignment_no', 'like', "%{$keyword}%")
                ->orWhere('ref_no', 'like', "%{$keyword}%")
                ->orWhereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->orWhereHas('customerLocation', function ($q) use ($keyword) {
                    $q->where('location_name', 'like', "%{$keyword}%");
                });
        });
    }

    /**
     * Default Sort Scope
     */
    public function scopeSortByDefault(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('assignment_year', $direction)
            ->orderBy('assignment_no', $direction);
    }

    /**
     * Create Work Assignment with Workers
     */
    public static function createWithWorkers(array $data): self
    {
        return self::executeInTransaction(function () use ($data) {
            $assignmentData = array_diff_key($data, array_flip(['workers']));
            $workAssignment = self::create($assignmentData);

            if (isset($data['workers'])) {
                foreach ($data['workers'] as $worker) {
                    $workAssignment->workers()->create($worker);
                }
            }

            return $workAssignment->load('workers');
        });
    }

    /**
     * Update Work Assignment with Workers
     */
    public function updateWithWorkers(array $data): self
    {
        return self::executeInTransaction(function () use ($data) {
            $assignmentData = array_diff_key($data, array_flip(['workers']));
            $this->update($assignmentData);

            if (isset($data['workers'])) {
                $existingWorkerIds = [];

                foreach ($data['workers'] as $workerData) {
                    if (isset($workerData['id']) && $workerData['id']) {
                        // Update existing worker
                        $worker = $this->workers()->find($workerData['id']);
                        if ($worker) {
                            $worker->update($workerData);
                            $existingWorkerIds[] = $workerData['id'];
                        }
                    } else {
                        // Create new worker
                        $newWorker = $this->workers()->create($workerData);
                        $existingWorkerIds[] = $newWorker->id;
                    }
                }

                // Delete workers that are not in the request
                $this->workers()->whereNotIn('id', $existingWorkerIds)->delete();
            }

            return $this->load('workers');
        });
    }
}

