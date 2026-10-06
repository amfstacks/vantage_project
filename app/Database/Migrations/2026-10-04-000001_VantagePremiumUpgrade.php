<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VantagePremiumUpgrade extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('properties') && ! $this->db->fieldExists('view_count', 'properties')) {
            $this->forge->addColumn('properties', [
                'view_count' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'default' => 0,
                    'after' => 'meta_description',
                ],
            ]);
        }

        if (! $this->db->tableExists('property_requests')) {
            $this->forge->addField([
                'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
                'property_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
                'request_reference' => ['type' => 'VARCHAR', 'constraint' => 32],
                'full_name' => ['type' => 'VARCHAR', 'constraint' => 120],
                'phone' => ['type' => 'VARCHAR', 'constraint' => 30],
                'email' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
                'request_type' => ['type' => 'ENUM', 'constraint' => ['information', 'viewing', 'call', 'offer'], 'default' => 'information'],
                'preferred_date' => ['type' => 'DATE', 'null' => true],
                'message' => ['type' => 'TEXT', 'null' => true],
                'status' => ['type' => 'ENUM', 'constraint' => ['new', 'contacted', 'scheduled', 'closed'], 'default' => 'new'],
                'source_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'client_ip_hash' => ['type' => 'CHAR', 'constraint' => 64, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('request_reference');
            $this->forge->addKey('property_id');
            $this->forge->addKey(['status', 'created_at']);
            $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('property_requests', true);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('property_requests')) {
            $this->forge->dropTable('property_requests', true);
        }
        if ($this->db->tableExists('properties') && $this->db->fieldExists('view_count', 'properties')) {
            $this->forge->dropColumn('properties', 'view_count');
        }
    }
}
