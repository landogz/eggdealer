<?php

namespace Database\Seeders;

use App\Models\EggSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EggSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['size_name' => 'XS', 'description' => 'Extra small eggs'],
            ['size_name' => 'S',  'description' => 'Small eggs'],
            ['size_name' => 'M',  'description' => 'Medium eggs'],
            ['size_name' => 'L',  'description' => 'Large eggs'],
            ['size_name' => 'XL', 'description' => 'Extra large eggs'],
            ['size_name' => 'XXL','description' => 'Jumbo eggs'],
        ];

        foreach ($sizes as $data) {
            EggSize::updateOrCreate(
                ['size_name' => $data['size_name']],
                ['description' => $data['description'], 'is_active' => true],
            );
        }
    }
}
