<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseOrder;

class PurchaseOrderPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, PurchaseOrder $purchaseOrder)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    public function update(User $user, PurchaseOrder $purchaseOrder)
    {
        return ($user->hasRole('admin') || $user->hasRole('manager')) 
            && $purchaseOrder->status === 'pending';
    }

    public function delete(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasRole('admin') && $purchaseOrder->status === 'pending';
    }

    public function approve(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    public function receive(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }
}
