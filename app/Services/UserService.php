<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    /**
     * Get paginated users with optional search and filter.
     */
    public function getPaginatedUsers(
        int $perPage = 15,
        ?string $search = null,
        ?string $usertype = null
    ): LengthAwarePaginator {
        $query = User::query();

        // Search by name, email, or phone
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by usertype
        if ($usertype) {
            $query->where('usertype', $usertype);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    /**
     * Get user by ID.
     */
    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    /**
     * Update user.
     */
    public function updateUser(int $id, array $data): User
    {
        $user = User::findOrFail($id);

        // Only hash password if it's being updated
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $user->fresh();
    }

    /**
     * Delete user.
     */
    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);

        return $user->delete();
    }
}
