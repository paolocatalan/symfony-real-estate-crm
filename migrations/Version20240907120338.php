<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907120338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE broker (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone INT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, broker_id INT DEFAULT NULL, agent_id INT DEFAULT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longtitude DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, features LONGTEXT NOT NULL, image_path VARCHAR(255) NOT NULL, bedrooms INT NOT NULL, bathrooms INT NOT NULL, sqft DOUBLE PRECISION NOT NULL, acres DOUBLE PRECISION NOT NULL, build_year INT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_8BF21CDE6CC064FC (broker_id), INDEX IDX_8BF21CDE3414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, phone INT NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE6CC064FC FOREIGN KEY (broker_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE3414710B FOREIGN KEY (agent_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE6CC064FC');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE3414710B');
        $this->addSql('DROP TABLE broker');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
