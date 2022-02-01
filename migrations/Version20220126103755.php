<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126103755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE types ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE types ADD CONSTRAINT FK_5930893012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_5930893012469DE2 ON types (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE types DROP FOREIGN KEY FK_5930893012469DE2');
        $this->addSql('DROP INDEX IDX_5930893012469DE2 ON types');
        $this->addSql('ALTER TABLE types DROP category_id');
    }
}
