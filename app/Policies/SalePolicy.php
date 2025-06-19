<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sale;

class SalePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Sale $sale)
    {
        return true;
    }

    public function create(User $user)
    {
        return true; // All authenticated users can create sales
    }

    public function update(User $user, Sale $sale)
    {
        return $user->hasRole('admin'); // Only admins can modify sales
    }

    public function delete(User $user, Sale $sale)
    {
        return $user->hasRole('admin');
    }
}
