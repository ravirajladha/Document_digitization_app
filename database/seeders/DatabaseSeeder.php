<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\State;
use App\Models\Set;
use App\Models\Receiver_type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'type' => 'admin',
            // Add other fields as necessary
        ]);

        User::create([
            'name' => ' User1', // Replace with the new user's name
            'email' => 'user1@gmail.com', // Replace with the new user's email
            'password' => Hash::make('admin'), // Replace 'password' with the desired password
            'type' => 'user', // or any other type if applicable
            // Add other fields as necessary
        ]);

        Set::create([
            'name' => 'set1',
            'created_by' => 1, // or appropriate user ID if necessary
            'status' => 1,
          
            'created_at' => Carbon::create(2024, 1, 10, 5, 0, 33),
            'updated_at' => Carbon::create(2024, 1, 10, 5, 36, 9),
        ]);
        Receiver_type::create([
            'name' => 'accountant',
            'created_by' => 1, // or appropriate user ID if necessary
            'status' => 1,
          
            'created_at' => Carbon::create(2024, 1, 10, 5, 0, 33),
            'updated_at' => Carbon::create(2024, 1, 10, 5, 36, 9),
        ]);
        {
            $states = [
                'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
                'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
                'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
                'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu',
                'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
                // Add any additional states or territories
            ];
    
            foreach ($states as $stateName) {
                State::create(['name' => $stateName]);
            }
        }
    }
}
