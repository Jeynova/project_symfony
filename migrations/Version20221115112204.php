<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221115112204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receipe ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE receipe ADD CONSTRAINT FK_392996B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_392996B7A76ED395 ON receipe (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receipe DROP FOREIGN KEY FK_392996B7A76ED395');
        $this->addSql('DROP INDEX IDX_392996B7A76ED395 ON receipe');
        $this->addSql('ALTER TABLE receipe DROP user_id');
    }
}
