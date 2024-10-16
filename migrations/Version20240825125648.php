<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240825125648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE6CC064FC');
        $this->addSql('DROP INDEX UNIQ_8BF21CDE6CC064FC ON property');
        $this->addSql('ALTER TABLE property CHANGE broker_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDEA76ED395 ON property (user_id)');
        $this->addSql('ALTER TABLE user ADD phone INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEA76ED395');
        $this->addSql('DROP INDEX UNIQ_8BF21CDEA76ED395 ON property');
        $this->addSql('ALTER TABLE property CHANGE user_id broker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE6CC064FC FOREIGN KEY (broker_id) REFERENCES broker (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDE6CC064FC ON property (broker_id)');
        $this->addSql('ALTER TABLE user DROP phone');
    }
}
