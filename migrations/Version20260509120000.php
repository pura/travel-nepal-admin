<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add PublicTrip-oriented fields to itinerary templates and days (nullable; no data loss).
 */
final class Version20260509120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add description, pricing, images, stats, services, and richer day fields for public trip export.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE itinerary_template
                ADD description LONGTEXT DEFAULT NULL,
                ADD price_amount VARCHAR(32) DEFAULT NULL,
                ADD price_currency VARCHAR(8) DEFAULT NULL,
                ADD hero_image_url VARCHAR(2048) DEFAULT NULL,
                ADD hero_image_path VARCHAR(512) DEFAULT NULL,
                ADD gallery_image_urls JSON DEFAULT NULL,
                ADD total_distance_km INT DEFAULT NULL,
                ADD altitude_max_m INT DEFAULT NULL,
                ADD altitude_min_m INT DEFAULT NULL,
                ADD services_included JSON DEFAULT NULL,
                ADD services_excluded JSON DEFAULT NULL,
                ADD services_optional JSON DEFAULT NULL
            SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE itinerary_template_day
                ADD details LONGTEXT DEFAULT NULL,
                ADD meals VARCHAR(255) DEFAULT NULL,
                ADD destination_name VARCHAR(255) DEFAULT NULL
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE itinerary_template
                DROP description,
                DROP price_amount,
                DROP price_currency,
                DROP hero_image_url,
                DROP hero_image_path,
                DROP gallery_image_urls,
                DROP total_distance_km,
                DROP altitude_max_m,
                DROP altitude_min_m,
                DROP services_included,
                DROP services_excluded,
                DROP services_optional
            SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE itinerary_template_day
                DROP details,
                DROP meals,
                DROP destination_name
            SQL);
    }
}
