<?php

namespace App\Policies;

use App\Library\Enums\UserRole;
use Illuminate\Auth\Access\Response;

class CurrencyPolicy extends BasePolicy
{
    public function view(): Response
    {
        return $this->checkRoles(UserRole::portal())
            ? Response::allow()
            : Response::deny($this->declinePermissionsMessage);
    }

    public function create(): Response
    {
        return $this->checkRoles(UserRole::portal())
            ? Response::allow()
            : Response::deny($this->declinePermissionsMessage);
    }

    public function update(): Response
    {
        return $this->checkRoles(UserRole::portal())
            ? Response::allow()
            : Response::deny($this->declinePermissionsMessage);
    }

    public function delete(): Response
    {
        return $this->checkRoles(['admin'])
            ? Response::allow()
            : Response::deny($this->declinePermissionsMessage);
    }
}
