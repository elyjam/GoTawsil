// database/seeders/DatabaseSeeder.php
<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        // Add more seeders if needed
    }
}
