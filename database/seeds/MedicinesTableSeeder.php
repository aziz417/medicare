<?php

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = database_path('data/medicines.json');
        $data = file_exists($path) ? file_get_contents($path) : "[]";
        foreach (json_decode($data, true) ?? [] as $item) {
            Medicine::create($item);
        }
    }
}
