<?php

namespace App\Models;

use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $fillable = [
        'customer_no',
        'name',
        'phone_number',
        'address',
    ];

    /**
     * Get customer locations
     */
    public function locations(): HasMany
    {
        return $this->hasMany(CustomerLocation::class);
    }

    /**
     * Get customer quotations
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * Get customer purchase orders
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Get customer work assignments
     */
    public function workAssignments(): HasMany
    {
        return $this->hasMany(WorkAssignment::class);
    }

    /**
     * Get customer daily activities
     */
    public function dailyActivities(): HasMany
    {
        return $this->hasMany(DailyActivity::class);
    }

    /**
     * Get customer delivery notes
     */
    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(DeliveryNote::class);
    }

    /**
     * Define searchable columns for customer search
     * Replaces CustomerRepository::search() logic
     */
    protected function searchableColumns(): array
    {
        return ['name', 'address', 'phone_number'];
    }

    /**
     * Scope: Search with location support
     * Enhanced search that also looks in related locations
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('address', 'like', "%{$keyword}%")
                ->orWhere('phone_number', 'like', "%{$keyword}%")
                ->orWhereHas('locations', function ($q) use ($keyword) {
                    $q->where('location_name', 'like', "%{$keyword}%");
                });
        });
    }

    /**
     * Scope: Eager load locations
     * Replaces CustomerRepository::withLocations()
     */
    public function scopeWithLocations(Builder $query): Builder
    {
        return $query->with('locations');
    }

    /**
     * Create customer with nested locations
     * Replaces CustomerService::createCustomer()
     */
    public static function createWithLocations(array $data): self
    {
        return self::executeInTransaction(function () use ($data) {
            $customerData = array_diff_key($data, array_flip(['customer_locations']));
            $customer = self::create($customerData);

            if (isset($data['customer_locations'])) {
                foreach ($data['customer_locations'] as $location) {
                    $customer->locations()->create($location);
                }
            }

            return $customer->load('locations');
        });
    }

    /**
     * Update customer with nested locations management
     * Replaces CustomerService::updateCustomer()
     */
    public function updateWithLocations(array $data): self
    {
        return self::executeInTransaction(function () use ($data) {
            $customerData = array_diff_key($data, array_flip(['customer_locations']));
            $this->update($customerData);

            if (isset($data['customer_locations'])) {
                $existingLocationIds = [];

                foreach ($data['customer_locations'] as $locationData) {
                    if (isset($locationData['id']) && $locationData['id']) {
                        // Update existing location
                        $location = $this->locations()->find($locationData['id']);
                        if ($location) {
                            $location->update($locationData);
                            $existingLocationIds[] = $locationData['id'];
                        }
                    } else {
                        // Create new location
                        $newLocation = $this->locations()->create($locationData);
                        $existingLocationIds[] = $newLocation->id;
                    }
                }

                // Delete locations that are not in the request
                $this->locations()->whereNotIn('id', $existingLocationIds)->delete();
            }

            return $this->load('locations');
        });
    }
}

