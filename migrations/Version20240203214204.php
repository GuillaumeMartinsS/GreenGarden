<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203214204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3C0918EAA76ED395 ON garden (user_id)');
        $this->addSql('ALTER TABLE plant ADD garden_id INT NOT NULL, ADD genre_id INT NOT NULL');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D7239F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D724296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_AB030D7239F3B087 ON plant (garden_id)');
        $this->addSql('CREATE INDEX IDX_AB030D724296D31F ON plant (genre_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EAA76ED395');
        $this->addSql('DROP INDEX IDX_3C0918EAA76ED395 ON garden');
        $this->addSql('ALTER TABLE garden DROP user_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D7239F3B087');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D724296D31F');
        $this->addSql('DROP INDEX IDX_AB030D7239F3B087 ON plant');
        $this->addSql('DROP INDEX IDX_AB030D724296D31F ON plant');
        $this->addSql('ALTER TABLE plant DROP garden_id, DROP genre_id');
    }
}
