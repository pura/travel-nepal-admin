<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Hotels belong to a Region, not a Destination.
 */
final class Version20260317120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Link hotel to region instead of destination';
    }

    public function up(Schema $schema): void
    {
        // Ensure at least one region exists so existing hotels can be assigned.
        $this->addSql("INSERT INTO region (country, name, slug, is_active, created_at, updated_at)
            SELECT 'Nepal', 'Default (migration)', '__migration_default_hotel_region__', true, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
            WHERE NOT EXISTS (SELECT 1 FROM region)");

        $this->addSql('ALTER TABLE hotel ADD region_id INT DEFAULT NULL');
        $this->addSql('UPDATE hotel SET region_id = (SELECT id FROM region ORDER BY id ASC LIMIT 1)');
        $this->addSql('ALTER TABLE hotel ALTER COLUMN region_id SET NOT NULL');

        $this->addSql('CREATE INDEX idx_hotel_region ON hotel (region_id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT fk_hotel_region FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE hotel DROP CONSTRAINT FK_3535ED9816C6140');
        $this->addSql('DROP INDEX IDX_3535ED9816C6140');
        $this->addSql('ALTER TABLE hotel DROP COLUMN destination_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE hotel ADD destination_id INT DEFAULT NULL');
        $this->addSql("INSERT INTO destination (name, slug, short_description, long_description, region, activity_tags, best_months, min_days, max_days, budget_level, difficulty_level, is_active, created_at, updated_at)
            SELECT 'Default (migration)', '__migration_default_destination__', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
            WHERE NOT EXISTS (SELECT 1 FROM destination)");
        $this->addSql('UPDATE hotel SET destination_id = (SELECT id FROM destination ORDER BY id ASC LIMIT 1)');
        $this->addSql('ALTER TABLE hotel ALTER COLUMN destination_id SET NOT NULL');

        $this->addSql('CREATE INDEX IDX_3535ED9816C6140 ON hotel (destination_id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED9816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE hotel DROP CONSTRAINT fk_hotel_region');
        $this->addSql('DROP INDEX idx_hotel_region');
        $this->addSql('ALTER TABLE hotel DROP COLUMN region_id');
    }
}
