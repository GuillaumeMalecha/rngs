<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230918114112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_produit_commande DROP FOREIGN KEY FK_AC9D613182EA2E54');
        $this->addSql('ALTER TABLE commande_produit_commande DROP FOREIGN KEY FK_AC9D6131FCF26AD0');
        $this->addSql('ALTER TABLE produit_produit_commande DROP FOREIGN KEY FK_B46CEBE6F347EFB');
        $this->addSql('ALTER TABLE produit_produit_commande DROP FOREIGN KEY FK_B46CEBE6FCF26AD0');
        $this->addSql('DROP TABLE commande_produit_commande');
        $this->addSql('DROP TABLE produit_produit_commande');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_produit_commande (commande_id INT NOT NULL, produit_commande_id INT NOT NULL, INDEX IDX_AC9D613182EA2E54 (commande_id), INDEX IDX_AC9D6131FCF26AD0 (produit_commande_id), PRIMARY KEY(commande_id, produit_commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit_produit_commande (produit_id INT NOT NULL, produit_commande_id INT NOT NULL, INDEX IDX_B46CEBE6F347EFB (produit_id), INDEX IDX_B46CEBE6FCF26AD0 (produit_commande_id), PRIMARY KEY(produit_id, produit_commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_produit_commande ADD CONSTRAINT FK_AC9D613182EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produit_commande ADD CONSTRAINT FK_AC9D6131FCF26AD0 FOREIGN KEY (produit_commande_id) REFERENCES produit_commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_produit_commande ADD CONSTRAINT FK_B46CEBE6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_produit_commande ADD CONSTRAINT FK_B46CEBE6FCF26AD0 FOREIGN KEY (produit_commande_id) REFERENCES produit_commande (id) ON DELETE CASCADE');
    }
}
