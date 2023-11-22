<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122144423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT fk_723705d1a56723e4');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT fk_723705d167d74803');
        $this->addSql('DROP INDEX idx_723705d167d74803');
        $this->addSql('DROP INDEX idx_723705d1a56723e4');
        $this->addSql('ALTER TABLE transaction ADD currency_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction DROP currency_from_id');
        $this->addSql('ALTER TABLE transaction DROP currency_to_id');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D138248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_723705D138248176 ON transaction (currency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D138248176');
        $this->addSql('DROP INDEX IDX_723705D138248176');
        $this->addSql('ALTER TABLE transaction ADD currency_to_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction RENAME COLUMN currency_id TO currency_from_id');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT fk_723705d1a56723e4 FOREIGN KEY (currency_from_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT fk_723705d167d74803 FOREIGN KEY (currency_to_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_723705d167d74803 ON transaction (currency_to_id)');
        $this->addSql('CREATE INDEX idx_723705d1a56723e4 ON transaction (currency_from_id)');
    }
}
