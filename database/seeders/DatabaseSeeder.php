<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run()
    {
        $fake = Faker\Factory::create('id_ID');
        $fakefr = Faker\Factory::create('fr_FR');

        foreach (range(1, 5) as $i) {
            \App\Models\Category::create([
                'name' => 'categore ' . $fake->word(1),
            ]);
        }

        foreach (range(1, 4) as $i) {
            \App\Models\Unit::create([
                'name' => 'unit ' . $fake->word(1),
            ]);
        }

        foreach (range(1, 4) as $i) {
            \App\Models\Supplier::create(
                ['name' => $fakefr->lastName . '_' . $fakefr->word(1)],
            );
        }

        \App\Models\Role::create(
            ['name' => 'ADMIN'],
        );
        \App\Models\Role::create(
            ['name' => 'CASHIER'],
        );
        \App\Models\Role::create(
            ['name' => 'AW'],
        );
        \App\Models\Role::create(
            ['name' => 'AE'],
        );

        // Create people
        $peeps = [];
        foreach (range(1, 6) as $i) {
            $peeps[] = \App\Models\Person::create([
                'first_name' => $fake->firstName,
                'last_name' => $fake->lastName,
                'phone' => $fake->e164PhoneNumber,
                'address' => $fake->address
            ]);
        }

        $peeps[1]->employee()->create([
            'email' => 'a1@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 1 // 'ADMIN'
        ]);

        $peeps[2]->employee()->create([
            'email' => 'c1@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 2
        ]);

        $peeps[3]->employee()->create([
            'email' => 'c2@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 2
        ]);


        $peeps[5]->customer()->create([
            'email' => $fake->optional()->email,
            'customer_code' => $fake->unique()->isbn10
        ]);

        // Iterate through people and make employees and customer
        // foreach ($peeps as $p) {
        //     $p->employee()->create([
        //         'email' => $fake->unique()->email,
        //         'password' => Hash::make('password'),
        //         'role_id' => rand(1, 2)
        //     ]);
        //     $p->customer()->create([
        //         'email' => $fake->optional()->email,
        //         'customer_code' => $fake->unique()->isbn10
        //     ]);
        // }

        // Make Products
        $prods = [];
        foreach (range(1, 10) as $i) {
            $prods[] = \App\Models\Product::create([
                'name' => $fake->firstName
            ]);
        }

        // Iterate through products and make items
        foreach ($prods as $p) {
            for ($i = 0; $i < rand(1, 6); $i++) {
                $p->items()->create([
                    'item_name' => $p->name . ' ' . $fake->city,
                    'item_code' => $fake->isbn10,
                    'selling_price' => rand(200, 99999),
                    'capital_price' => rand(200, 99999),
                    'unit_id' => 1,
                    'category_id' => 1,
                    'employee_id' => 1,
                    'supplier_id' => 2
                ]);
            }
        }
    }
}
