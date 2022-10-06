<?php

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $path = database_path('data/departments.json');
        $data = file_exists($path) ? file_get_contents($path) : "[]";
        foreach (json_decode($data, true) ?? [] as $item) {
            Department::create($item);
        }
    }
}
