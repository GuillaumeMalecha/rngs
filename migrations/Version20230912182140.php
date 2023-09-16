<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912182140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'correction de la relation entre promotions et categorie';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotions ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotions ADD CONSTRAINT FK_C11D7DD1BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD1BCF5E72D ON promotions (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotions DROP FOREIGN KEY FK_C11D7DD1BCF5E72D');
        $this->addSql('DROP INDEX IDX_C11D7DD1BCF5E72D ON promotions');
        $this->addSql('ALTER TABLE promotions DROP categorie_id');
    }
}
