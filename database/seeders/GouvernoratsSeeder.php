<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GouvernoratsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            foreach (config('locations.emirates') as $index => $name) {
                if (DB::table('gouvernorats')->where('nom', $name)->exists()) {
                    continue;
                }
                DB::table('gouvernorats')->insert([
                    'nom' => $name,
                    // Internal identifiers; UAE carrier codes require a separate mapping.
                    'id_in_api' => 10001 + $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
