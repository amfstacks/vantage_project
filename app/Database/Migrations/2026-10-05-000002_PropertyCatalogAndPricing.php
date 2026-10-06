<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PropertyCatalogAndPricing extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('property_purposes')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
                'name' => ['type' => 'VARCHAR', 'constraint' => 100],
                'slug' => ['type' => 'VARCHAR', 'constraint' => 120],
                'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'sort_order' => ['type' => 'INT', 'default' => 0],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('name');
            $this->forge->addUniqueKey('slug');
            $this->forge->createTable('property_purposes', true);
        }

        if (! $this->db->tableExists('property_types')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
                'name' => ['type' => 'VARCHAR', 'constraint' => 100],
                'slug' => ['type' => 'VARCHAR', 'constraint' => 120],
                'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'sort_order' => ['type' => 'INT', 'default' => 0],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('name');
            $this->forge->addUniqueKey('slug');
            $this->forge->createTable('property_types', true);
        }

        $purposeModel = $this->db->table('property_purposes');
        foreach ([
            ['name' => 'For Sale', 'slug' => 'sale', 'description' => 'Properties available for outright purchase.', 'is_active' => 1, 'sort_order' => 10],
            ['name' => 'For Rent', 'slug' => 'rent', 'description' => 'Properties available for rental.', 'is_active' => 1, 'sort_order' => 20],
            ['name' => 'Shortlet (Daily)', 'slug' => 'shortlet', 'description' => 'Short-stay and daily accommodation.', 'is_active' => 1, 'sort_order' => 30],
        ] as $row) {
            if (! $purposeModel->where('slug', $row['slug'])->get()->getRow()) {
                $purposeModel->insert($row);
            }
        }

        if ($this->db->tableExists('properties')) {
            // Converting the legacy ENUM to VARCHAR allows future purposes to be created from Admin.
            $this->db->query('ALTER TABLE `properties` MODIFY COLUMN `purpose` VARCHAR(120) NOT NULL');

            if (! $this->db->fieldExists('purpose_id', 'properties')) {
                $this->forge->addColumn('properties', [
                    'purpose_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true, 'after' => 'purpose'],
                ]);
            }
            if (! $this->db->fieldExists('property_type_id', 'properties')) {
                $this->forge->addColumn('properties', [
                    'property_type_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true, 'after' => 'property_type'],
                ]);
            }

            $legacyTypes = $this->db->table('properties')
                ->select('property_type')
                ->where('property_type !=', '')
                ->groupBy('property_type')
                ->get()->getResult();
            foreach ($legacyTypes as $type) {
                $name = trim((string) $type->property_type);
                if ($name === '') {
                    continue;
                }
                $slug = url_title($name, '-', true) ?: 'property-type';
                $exists = $this->db->table('property_types')->groupStart()->where('name', $name)->orWhere('slug', $slug)->groupEnd()->get()->getRow();
                if (! $exists) {
                    $this->db->table('property_types')->insert(['name' => $name, 'slug' => $slug, 'is_active' => 1, 'sort_order' => 100]);
                }
            }

            $this->db->query("UPDATE properties p JOIN property_purposes pp ON pp.slug = LOWER(REPLACE(REPLACE(REPLACE(TRIM(p.purpose),' ','-'),'/','-'),'_','-')) SET p.purpose_id = pp.id WHERE p.purpose_id IS NULL");
            $this->db->query("UPDATE properties p JOIN property_types pt ON LOWER(pt.name) = LOWER(TRIM(p.property_type)) SET p.property_type_id = pt.id WHERE p.property_type_id IS NULL");
        }

        if ($this->db->tableExists('property_prices')) {
            if (! $this->db->fieldExists('purpose_id', 'property_prices')) {
                $this->forge->addColumn('property_prices', [
                    'purpose_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true, 'after' => 'property_id'],
                ]);
            }
            if (! $this->db->fieldExists('discount_percentage', 'property_prices')) {
                $this->forge->addColumn('property_prices', [
                    'discount_percentage' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'after' => 'discount_price'],
                ]);
            }
            $this->db->query('UPDATE property_prices SET discount_percentage = ROUND(((price - discount_price) / price) * 100, 2) WHERE price > 0 AND discount_price IS NOT NULL AND discount_price > 0 AND discount_price < price');
        }
    }

    public function down()
    {
        if ($this->db->tableExists('property_prices')) {
            if ($this->db->fieldExists('discount_percentage', 'property_prices')) {
                $this->forge->dropColumn('property_prices', 'discount_percentage');
            }
            if ($this->db->fieldExists('purpose_id', 'property_prices')) {
                $this->forge->dropColumn('property_prices', 'purpose_id');
            }
        }

        if ($this->db->tableExists('properties')) {
            if ($this->db->fieldExists('property_type_id', 'properties')) {
                $this->forge->dropColumn('properties', 'property_type_id');
            }
            if ($this->db->fieldExists('purpose_id', 'properties')) {
                $this->forge->dropColumn('properties', 'purpose_id');
            }
        }

        if ($this->db->tableExists('property_types')) {
            $this->forge->dropTable('property_types', true);
        }
        if ($this->db->tableExists('property_purposes')) {
            $this->forge->dropTable('property_purposes', true);
        }
    }
}
