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

    public function getPaginatedDailyActivities(int $perPage = 15, ?int $userId = null, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->dailyActivityRepository->withCustomerOnly();

        if ($userId) {
            $query->filterByUser($userId);
        }

        if ($search) {
            $query->search($search);
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
                foreach ($data['members'] as $employeeId) {
                    $dailyActivity->members()->create(['employee_id' => $employeeId]);
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

            // Update daily activity only if there's data
            if (!empty($activityData)) {
                $dailyActivity = $this->dailyActivityRepository->update($id, $activityData);
            } else {
                $dailyActivity = $this->dailyActivityRepository->find($id);
            }

            // Update members with partial update support
            if (isset($data['members'])) {
                $existingMemberIds = [];

                foreach ($data['members'] as $memberData) {
                    if (isset($memberData['id']) && $memberData['id']) {
                        // Update existing member
                        $member = $dailyActivity->members()->find($memberData['id']);
                        if ($member) {
                            $member->update(['employee_id' => $memberData['employee_id']]);
                            $existingMemberIds[] = $memberData['id'];
                        }
                    } else {
                        // Create new member
                        $newMember = $dailyActivity->members()->create(['employee_id' => $memberData['employee_id']]);
                        $existingMemberIds[] = $newMember->id;
                    }
                }

                // Delete members that are not in the request
                $dailyActivity->members()->whereNotIn('id', $existingMemberIds)->delete();
            }

            // Update descriptions with partial update support
            if (isset($data['descriptions'])) {
                $existingDescriptionIds = [];

                foreach ($data['descriptions'] as $descriptionData) {
                    if (isset($descriptionData['id']) && $descriptionData['id']) {
                        // Update existing description
                        $description = $dailyActivity->descriptions()->find($descriptionData['id']);
                        if ($description) {
                            $description->update($descriptionData);
                            $existingDescriptionIds[] = $descriptionData['id'];
                        }
                    } else {
                        // Create new description
                        $newDescription = $dailyActivity->descriptions()->create($descriptionData);
                        $existingDescriptionIds[] = $newDescription->id;
                    }
                }

                // Delete descriptions that are not in the request
                $dailyActivity->descriptions()->whereNotIn('id', $existingDescriptionIds)->delete();
            }

            return $dailyActivity->load(['members', 'descriptions']);
        });
    }

    public function deleteDailyActivity(int $id): bool
    {
        return $this->dailyActivityRepository->delete($id);
    }
}
