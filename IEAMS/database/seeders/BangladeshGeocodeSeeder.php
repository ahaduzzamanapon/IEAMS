<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BangladeshGeocodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Divisions
        $this->command->info('Seeding Divisions from GitHub...');
        $divisionsUrl = 'https://raw.githubusercontent.com/nuhil/bangladesh-geocode/master/divisions/divisions.json';
        $response = Http::get($divisionsUrl);
        if ($response->successful()) {
            $decoded = $response->json();
            $data = $this->extractData($decoded, 'divisions');
            if ($data) {
                foreach ($data as $item) {
                    Division::updateOrCreate(
                        ['id' => $item['id']],
                        [
                            'name' => $item['name'],
                            'bn_name' => $item['bn_name'] ?? null,
                            'url' => $item['url'] ?? null,
                        ]
                    );
                }
                $this->command->info('Seeded ' . count($data) . ' divisions.');
            } else {
                $this->command->error('Could not find divisions table data array in JSON.');
            }
        } else {
            $this->command->error('Failed to download divisions JSON from GitHub.');
        }

        // 2. Seed Districts
        $this->command->info('Seeding Districts from GitHub...');
        $districtsUrl = 'https://raw.githubusercontent.com/nuhil/bangladesh-geocode/master/districts/districts.json';
        $response = Http::get($districtsUrl);
        if ($response->successful()) {
            $decoded = $response->json();
            $data = $this->extractData($decoded, 'districts');
            if ($data) {
                foreach ($data as $item) {
                    District::updateOrCreate(
                        ['id' => $item['id']],
                        [
                            'division_id' => $item['division_id'],
                            'name' => $item['name'],
                            'bn_name' => $item['bn_name'] ?? null,
                            'lat' => $item['lat'] ?? null,
                            'lon' => $item['lon'] ?? null,
                            'url' => $item['url'] ?? null,
                        ]
                    );
                }
                $this->command->info('Seeded ' . count($data) . ' districts.');
            } else {
                $this->command->error('Could not find districts table data array in JSON.');
            }
        } else {
            $this->command->error('Failed to download districts JSON from GitHub.');
        }

        // 3. Seed Upazilas
        $this->command->info('Seeding Upazilas from GitHub...');
        $upazilasUrl = 'https://raw.githubusercontent.com/nuhil/bangladesh-geocode/master/upazilas/upazilas.json';
        $response = Http::get($upazilasUrl);
        if ($response->successful()) {
            $decoded = $response->json();
            $data = $this->extractData($decoded, 'upazilas');
            if ($data) {
                foreach ($data as $item) {
                    Upazila::updateOrCreate(
                        ['id' => $item['id']],
                        [
                            'district_id' => $item['district_id'],
                            'name' => $item['name'],
                            'bn_name' => $item['bn_name'] ?? null,
                            'url' => $item['url'] ?? null,
                        ]
                    );
                }
                $this->command->info('Seeded ' . count($data) . ' upazilas.');
            } else {
                $this->command->error('Could not find upazilas table data array in JSON.');
            }
        } else {
            $this->command->error('Failed to download upazilas JSON from GitHub.');
        }
    }

    /**
     * Extract the data array from the phpMyAdmin JSON export format
     */
    private function extractData($decoded, $tableName)
    {
        if (is_array($decoded)) {
            foreach ($decoded as $item) {
                if (isset($item['type']) && $item['type'] === 'table' && isset($item['name']) && $item['name'] === $tableName) {
                    return $item['data'];
                }
            }
        }
        return null;
    }
}
