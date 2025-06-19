<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'reference_type',
        'reference_id',
        'notes',
        'user_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Create stock movement and update product stock
    public static function createMovement($productId, $type, $quantity, $referenceType = null, $referenceId = null, $notes = null)
    {
        $product = Product::findOrFail($productId);
        $previousStock = $product->current_stock;
        
        if ($type === 'in') {
            $newStock = $previousStock + $quantity;
        } elseif ($type === 'out') {
            $newStock = $previousStock - $quantity;
        } else { // adjustment
            $newStock = $quantity;
        }

        // Update product stock
        $product->update(['current_stock' => $newStock]);

        // Create movement record
        return self::create([
            'product_id' => $productId,
            'type' => $type,
            'quantity' => $type === 'adjustment' ? ($newStock - $previousStock) : $quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'user_id' => auth()->id()
        ]);
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($movement) {
            $product = $movement->product;
            
            // Check for low stock after movement
            if ($product->current_stock <= $product->minimum_stock) {
                // Notify all admin users
                $adminUsers = \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'admin');
                })->get();
                
                foreach ($adminUsers as $admin) {
                    $admin->notify(new \App\Notifications\LowStockNotification($product));
                }
            }
        });
    }
}