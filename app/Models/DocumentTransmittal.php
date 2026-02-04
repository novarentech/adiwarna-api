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

class DocumentTransmittal extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $table = 'transmittals';

    protected $fillable = [
        'name',
        'ta_no',
        'date',
        'customer_id',
        'customer_district',
        'pic_name',
        'report_type',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
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

    public function documents(): HasMany
    {
        return $this->hasMany(TransmittalDocument::class, 'transmittal_id');
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
            $q->whereHas('customer', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })
                ->orWhere('pic_name', 'like', "%{$search}%");
        });
    }

    public function scopeSortDefault(Builder $query, string $direction = 'asc'): Builder
    {
        return $query
            // sort by year (2024)
            ->orderByRaw(
                "CAST(SUBSTRING_INDEX(ta_no, '/', -1) AS UNSIGNED) {$direction}"
            )
            // then sort by number (001)
            ->orderByRaw(
                "CAST(SUBSTRING_INDEX(ta_no, '/', 1) AS UNSIGNED) {$direction}"
            );
    }


    public function scopeFilterByDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with(['documents', 'customer']);
    }

    public function scopeWithCustomerOnly(Builder $query): Builder
    {
        return $query->with(['customer']);
    }

    /*
    |--------------------------------------------------------------------------
    | Transactional Methods
    |--------------------------------------------------------------------------
    */
    public static function createWithDocuments(array $data): self
    {
        return self::transactional(function () use ($data) {
            $transmittalData = array_diff_key($data, array_flip(['documents']));
            
            $transmittal = self::create($transmittalData);

            if (isset($data['documents'])) {
                foreach ($data['documents'] as $document) {
                    $transmittal->documents()->create($document);
                }
            }

            return $transmittal->load('documents', 'customer');
        });
    }

    public function updateWithDocuments(array $data): self
    {
        return $this->transactional(function () use ($data) {
            $transmittalData = array_diff_key($data, array_flip(['documents']));

            $this->update($transmittalData);

            if (isset($data['documents'])) {
                $existingIds = [];
                foreach ($data['documents'] as $docData) {
                    if (isset($docData['id']) && $docData['id']) {
                        $this->documents()->where('id', $docData['id'])->update(array_diff_key($docData, array_flip(['id'])));
                        $existingIds[] = $docData['id'];
                    } else {
                        // Original logic didn't check for "existing but not ID" because these are usually files or simple records
                        $newDoc = $this->documents()->create($docData);
                        $existingIds[] = $newDoc->id;
                    }
                }
                // Delete removed
                $this->documents()->whereNotIn('id', $existingIds)->delete();
            }

            return $this->load('documents', 'customer');
        });
    }
}
