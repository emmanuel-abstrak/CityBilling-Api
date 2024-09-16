<?php

namespace App\Policies;

use App\Models\User;

class BasePolicy
{
    protected User $user;
    protected string $declinePermissionsMessage = 'You do not have permissions';
    public function __construct()
    {
        /** @var User $user */
        $user = auth()->user();
        $this->user = $user;
    }

    protected function checkRoles(array $roles): bool
    {
        return in_array($this->user->getAttribute('role'), $roles);
    }
}
