<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222092057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE post_user DROP id');
        $this->addSql('ALTER TABLE post_user ADD PRIMARY KEY (post_id, user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE post_user ADD id INT NOT NULL');
        $this->addSql('ALTER TABLE post_user ADD PRIMARY KEY (id)');
    }
}
