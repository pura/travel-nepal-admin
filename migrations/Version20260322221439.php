<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260322221439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema from entity mappings (consolidated migration).';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE destination (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, short_description LONGTEXT DEFAULT NULL, long_description LONGTEXT DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, activity_tags JSON DEFAULT NULL, best_months JSON DEFAULT NULL, min_days INT DEFAULT NULL, max_days INT DEFAULT NULL, budget_level VARCHAR(50) DEFAULT NULL, difficulty_level VARCHAR(50) DEFAULT NULL, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_3EC63EAA989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE guide (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, guide_type VARCHAR(100) DEFAULT NULL, languages JSON DEFAULT NULL, regions_supported JSON DEFAULT NULL, daily_rate_from NUMERIC(12, 2) DEFAULT NULL, daily_rate_to NUMERIC(12, 2) DEFAULT NULL, specialties JSON DEFAULT NULL, certification_notes LONGTEXT DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_phone VARCHAR(50) DEFAULT NULL, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, supplier_id INT DEFAULT NULL, INDEX IDX_CA9EC7352ADD6D8C (supplier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(100) DEFAULT NULL, nightly_price_from NUMERIC(12, 2) DEFAULT NULL, nightly_price_to NUMERIC(12, 2) DEFAULT NULL, amenities JSON DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_phone VARCHAR(50) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, region_id INT NOT NULL, supplier_id INT DEFAULT NULL, INDEX IDX_3535ED998260155 (region_id), INDEX IDX_3535ED92ADD6D8C (supplier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE itinerary_template (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, trip_type VARCHAR(100) DEFAULT NULL, duration_days INT DEFAULT NULL, budget_level VARCHAR(50) DEFAULT NULL, comfort_level VARCHAR(50) DEFAULT NULL, difficulty_level VARCHAR(50) DEFAULT NULL, interest_tags JSON DEFAULT NULL, summary LONGTEXT DEFAULT NULL, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, starting_region_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_B6822B42989D9B62 (slug), INDEX IDX_B6822B429EA3008B (starting_region_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE itinerary_template_day (id INT AUTO_INCREMENT NOT NULL, day_number INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, hotel_category VARCHAR(100) DEFAULT NULL, transport_type VARCHAR(100) DEFAULT NULL, guide_type VARCHAR(100) DEFAULT NULL, activity_notes LONGTEXT DEFAULT NULL, itinerary_template_id INT NOT NULL, destination_id INT DEFAULT NULL, INDEX IDX_8679E5AD6099834D (itinerary_template_id), INDEX IDX_8679E5AD816C6140 (destination_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F62F176989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE representative (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, whatsapp VARCHAR(50) DEFAULT NULL, languages JSON DEFAULT NULL, active_hours VARCHAR(255) DEFAULT NULL, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, supplier_type VARCHAR(100) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, contact_phone VARCHAR(50) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE supplier_region (supplier_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_D40023A22ADD6D8C (supplier_id), INDEX IDX_D40023A298260155 (region_id), PRIMARY KEY (supplier_id, region_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE transport_service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, service_type VARCHAR(100) DEFAULT NULL, vehicle_type VARCHAR(100) DEFAULT NULL, capacity INT DEFAULT NULL, base_area VARCHAR(255) DEFAULT NULL, price_notes LONGTEXT DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_phone VARCHAR(50) DEFAULT NULL, is_active TINYINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, supplier_id INT DEFAULT NULL, INDEX IDX_754A5A232ADD6D8C (supplier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE guide ADD CONSTRAINT FK_CA9EC7352ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED998260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED92ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE itinerary_template ADD CONSTRAINT FK_B6822B429EA3008B FOREIGN KEY (starting_region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE itinerary_template_day ADD CONSTRAINT FK_8679E5AD6099834D FOREIGN KEY (itinerary_template_id) REFERENCES itinerary_template (id)');
        $this->addSql('ALTER TABLE itinerary_template_day ADD CONSTRAINT FK_8679E5AD816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)');
        $this->addSql('ALTER TABLE supplier_region ADD CONSTRAINT FK_D40023A22ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_region ADD CONSTRAINT FK_D40023A298260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transport_service ADD CONSTRAINT FK_754A5A232ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide DROP FOREIGN KEY FK_CA9EC7352ADD6D8C');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED998260155');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED92ADD6D8C');
        $this->addSql('ALTER TABLE itinerary_template DROP FOREIGN KEY FK_B6822B429EA3008B');
        $this->addSql('ALTER TABLE itinerary_template_day DROP FOREIGN KEY FK_8679E5AD6099834D');
        $this->addSql('ALTER TABLE itinerary_template_day DROP FOREIGN KEY FK_8679E5AD816C6140');
        $this->addSql('ALTER TABLE supplier_region DROP FOREIGN KEY FK_D40023A22ADD6D8C');
        $this->addSql('ALTER TABLE supplier_region DROP FOREIGN KEY FK_D40023A298260155');
        $this->addSql('ALTER TABLE transport_service DROP FOREIGN KEY FK_754A5A232ADD6D8C');
        $this->addSql('DROP TABLE destination');
        $this->addSql('DROP TABLE guide');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE itinerary_template');
        $this->addSql('DROP TABLE itinerary_template_day');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE representative');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE supplier_region');
        $this->addSql('DROP TABLE transport_service');
    }
}
