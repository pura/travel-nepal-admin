<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260408121000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add website column to supplier.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE supplier ADD website VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE supplier DROP website');
    }
}
