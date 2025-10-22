<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Seeder;

class TrackerSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            Track::create(['title' => $i]);
        }
    }
}
