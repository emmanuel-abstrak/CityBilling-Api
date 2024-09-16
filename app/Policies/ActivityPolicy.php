<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

class ActivityPolicy extends BasePolicy
{
    public function view(): Response
    {
        return $this->checkRoles(['admin'])
            ? Response::allow()
            : Response::deny($this->declinePermissionsMessage);
    }

    public function create(): Response
    {
        return $this->checkRoles(['admin'])
            ? Response::allow()
            : Response::deny($this->declinePermissionsMessage);
    }

    public function update(): Response
    {
        return $this->checkRoles(['admin'])
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
