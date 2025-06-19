<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Low Stock Alert - ' . $this->product->name)
            ->line('The following product is running low on stock:')
            ->line('Product: ' . $this->product->name)
            ->line('SKU: ' . $this->product->sku)
            ->line('Current Stock: ' . $this->product->current_stock)
            ->line('Minimum Stock: ' . $this->product->minimum_stock)
            ->action('View Product', route('products.show', $this->product))
            ->line('Please reorder this product soon.');
    }

    public function toArray($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_stock' => $this->product->current_stock,
            'minimum_stock' => $this->product->minimum_stock,
        ];
    }
}
