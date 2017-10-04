<?php

use App\Eloquent\Endpoint;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EndpointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        factory(Endpoint::class, 10)->create()->each(function ($endpoint) use ($faker) {
            $endpoint->field()->create([
                'fields_name' => "name, description, image, is_active, status, start_time, count",
                'fields_type' => "string, text, image, boolean, array, date_time, number",
                'data' => '{}',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // $endpoint->method()->create([
            //     'index' => $faker->sentence(5),
            //     'create' => '{data: {items: $data}}',
            //     'show' => '{data: {item: $data}}',
            //     'update' => '{data: {item: $data}}',
            //     'delete' => '{data: {item: $data}}',
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ]);
        });
    }
}
