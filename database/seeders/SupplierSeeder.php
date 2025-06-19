<?php
namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => "Tech Solutions Ltd",
                'email' => "contact@techsolutions.com",
                'phone' => "+1234567890",
                'address' => "123 Tech Street, Silicon Valley",
                'contact_person' => "John Doe"
            ],
            [
                'name' => "Fashion World Inc",
                'email' => "orders@fashionworld.com",
                'phone' => "+1234567891",
                'address' => "456 Fashion Ave, New York",
                'contact_person' => "Jane Smith"
            ],
            [
                'name' => "Book Publishers Co",
                'email' => "sales@bookpublishers.com",
                'phone' => "+1234567892",
                'address' => "789 Library Lane, Boston",
                'contact_person' => "Mike Johnson"
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}