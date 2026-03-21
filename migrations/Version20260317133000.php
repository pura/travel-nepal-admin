<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260317133000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add starting_region_id to itinerary_template';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE itinerary_template ADD starting_region_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX idx_itinerary_template_starting_region ON itinerary_template (starting_region_id)');
        $this->addSql('ALTER TABLE itinerary_template ADD CONSTRAINT fk_itinerary_template_starting_region FOREIGN KEY (starting_region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE itinerary_template DROP CONSTRAINT fk_itinerary_template_starting_region');
        $this->addSql('DROP INDEX idx_itinerary_template_starting_region');
        $this->addSql('ALTER TABLE itinerary_template DROP COLUMN starting_region_id');
    }
}
