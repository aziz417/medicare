<?php

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = config('templates');
        foreach ($templates as $item) {
            Template::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
