<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // DRY: Define the master admin credentials
        $data = [
            'role'          => 'admin',
            'first_name'    => 'System',
            'last_name'     => 'Administrator',
            'email'         => 'admin@houseboxrealty.com',
            // Securely hash the default password ('admin123')
            'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        // Insert into the users table we created in Phase 2
        $this->db->table('users')->insert($data);
        
        echo "✅ Master Admin seeded successfully!\n";
    }
}