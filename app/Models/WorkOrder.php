<?php

namespace App\Models;

use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $table = 'work_orders';

    protected $fillable = [
        'work_order_no',
        'work_order_year',
        'date',
        'customer_id',
        'customer_location_id',
        'scope_of_work',
        'status', // Added based on Repository usage
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'work_order_year' => 'integer',
            'scope_of_work' => 'array',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerLocation(): BelongsTo
    {
        return $this->belongsTo(CustomerLocation::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'work_order_employees')
            ->withPivot('id')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    protected function searchableColumns(): array
    {
        // Complex search handled in scopeSearch
        return [];
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->whereHas('employees', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('customer', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('customerLocation', function ($subQ) use ($search) {
                    $subQ->where('location_name', 'like', "%{$search}%");
                })
                ->orWhereRaw('JSON_SEARCH(LOWER(scope_of_work), "one", LOWER(?)) IS NOT NULL', ["%{$search}%"]);
        });
    }

    public function scopeSortDefault(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('work_order_year', $direction)
                     ->orderBy('work_order_no', $direction);
    }

    public function scopeFilterByCustomer(Builder $query, int $customerId): Builder
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeFilterByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeFilterByDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with(['employees', 'customer', 'customerLocation']);
    }

    public function scopeSort(Builder $query, ?string $sortBy): Builder
    {
        if (!$sortBy) {
            return $this->scopeSortDefault($query);
        }

        if (str_contains($sortBy, ':')) {
            [$field, $direction] = explode(':', $sortBy);
        } else {
            $field = $sortBy;
            $direction = 'asc';
        }

        return match ($field) {
            'date_started' => $query->orderBy('date', $direction),
            'customer' => $query->leftJoin('customers', 'work_orders.customer_id', '=', 'customers.id')
                ->orderBy('customers.name', $direction)
                ->select('work_orders.*'),
            'work_location' => $query->leftJoin('customer_locations', 'work_orders.customer_location_id', '=', 'customer_locations.id')
                ->orderBy('customer_locations.location_name', $direction)
                ->select('work_orders.*'),
            'workers_name' => $query->leftJoin('work_order_employees', 'work_orders.id', '=', 'work_order_employees.work_order_id')
                ->leftJoin('employees', 'work_order_employees.employee_id', '=', 'employees.id')
                ->groupBy('work_orders.id')
                ->orderByRaw("MIN(employees.name) $direction")
                ->select('work_orders.*'),
            default => $query->orderBy($field, $direction),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Transactional Methods
    |--------------------------------------------------------------------------
    */
    public static function createWithEmployees(array $data): self
    {
        return self::transactional(function () use ($data) {
            $workOrderData = array_diff_key($data, array_flip(['employees']));
            
            $workOrder = self::create($workOrderData);

            if (isset($data['employees'])) {
                $employeeIds = collect($data['employees'])->pluck('employee_id')->filter()->toArray();
                $workOrder->employees()->attach($employeeIds);
            }

            return $workOrder->load(['employees', 'customer', 'customerLocation']);
        });
    }

    public function updateWithEmployees(array $data): self
    {
        return $this->transactional(function () use ($data) {
            $workOrderData = array_diff_key($data, array_flip(['employees']));

            $this->update($workOrderData);

            if (isset($data['employees'])) {
                // Using sync to simplify logic: replaces connections with new set
                $employeeIds = collect($data['employees'])->pluck('employee_id')->filter()->toArray();
                $this->employees()->sync($employeeIds);
            }

            return $this->load(['employees', 'customer', 'customerLocation']);
        });
    }
}
