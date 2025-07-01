<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('cities')->delete();

    $cities = [
      'Phnom Penh',
      'Banteay Meanchey',
      'Battambang',
      'Kampong Cham',
      'Kampong Chhnang',
      'Kampong Speu',
      'Kampong Thom',
      'Kampot',
      'Kandal',
      'Kep',
      'Koh Kong',
      'Kratié',
      'Mondulkiri',
      'Oddar Meanchey',
      'Pailin',
      'Preah Sihanouk',
      'Preah Vihear',
      'Prey Veng',
      'Pursat',
      'Ratanakiri',
      'Siem Reap',
      'Stung Treng',
      'Svay Rieng',
      'Takéo',
      'Tboung Khmum',
    ];

    foreach ($cities as $cityName) {
      City::create(['name' => $cityName]);
    }
  }
}
