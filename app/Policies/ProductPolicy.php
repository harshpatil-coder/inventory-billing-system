<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view products
    }

    public function view(User $user, Product $product)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    public function update(User $user, Product $product)
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    public function delete(User $user, Product $product)
    {
        return $user->hasRole('admin');
    }

    public function adjustStock(User $user, Product $product)
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }
}
