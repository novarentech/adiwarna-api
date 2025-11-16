<?php

namespace App\Services;

use App\Contracts\Repositories\DailyActivityRepositoryInterface;
use App\Models\DailyActivity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DailyActivityService extends BaseService
{
    public function __construct(
        protected DailyActivityRepositoryInterface $dailyActivityRepository
    ) {}

    public function getPaginatedDailyActivities(int $perPage = 15, ?int $userId = null): LengthAwarePaginator
    {
        $query = $this->dailyActivityRepository->withRelations();

        if ($userId) {
            $query->filterByUser($userId);
        }

        return $query->paginate($perPage);
    }

    public function getDailyActivityById(int $id): ?DailyActivity
    {
        return $this->dailyActivityRepository->withRelations()->find($id);
    }

    public function createDailyActivity(array $data): DailyActivity
    {
        return $this->executeInTransaction(function () use ($data) {
            $activityData = array_diff_key($data, array_flip(['members', 'descriptions']));
            $dailyActivity = $this->dailyActivityRepository->create($activityData);

            if (isset($data['members'])) {
                foreach ($data['members'] as $member) {
                    $dailyActivity->members()->create($member);
                }
            }

            if (isset($data['descriptions'])) {
                foreach ($data['descriptions'] as $description) {
                    $dailyActivity->descriptions()->create($description);
                }
            }

            return $dailyActivity->load(['members', 'descriptions']);
        });
    }

    public function updateDailyActivity(int $id, array $data): DailyActivity
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $activityData = array_diff_key($data, array_flip(['members', 'descriptions']));
            $dailyActivity = $this->dailyActivityRepository->update($id, $activityData);

            if (isset($data['members'])) {
                $dailyActivity->members()->delete();
                foreach ($data['members'] as $member) {
                    $dailyActivity->members()->create($member);
                }
            }

            if (isset($data['descriptions'])) {
                $dailyActivity->descriptions()->delete();
                foreach ($data['descriptions'] as $description) {
                    $dailyActivity->descriptions()->create($description);
                }
            }

            return $dailyActivity->load(['members', 'descriptions']);
        });
    }

    public function deleteDailyActivity(int $id): bool
    {
        return $this->dailyActivityRepository->delete($id);
    }
}
