<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508142230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_produitcommande (commande_id INT NOT NULL, produitcommande_id INT NOT NULL, INDEX IDX_BA9AACBC82EA2E54 (commande_id), INDEX IDX_BA9AACBC4899E721 (produitcommande_id), PRIMARY KEY(commande_id, produitcommande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_produitcommande (produit_id INT NOT NULL, produitcommande_id INT NOT NULL, INDEX IDX_FC570C6BF347EFB (produit_id), INDEX IDX_FC570C6B4899E721 (produitcommande_id), PRIMARY KEY(produit_id, produitcommande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_langue (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, traduction VARCHAR(255) DEFAULT NULL, INDEX IDX_6405BEB4F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, vendeurs_id INT DEFAULT NULL, clients_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nomutilisateur VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), UNIQUE INDEX UNIQ_1D1C63B36B274DD0 (vendeurs_id), UNIQUE INDEX UNIQ_1D1C63B3AB014612 (clients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_produitcommande ADD CONSTRAINT FK_BA9AACBC82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produitcommande ADD CONSTRAINT FK_BA9AACBC4899E721 FOREIGN KEY (produitcommande_id) REFERENCES produit_commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_produitcommande ADD CONSTRAINT FK_FC570C6BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_produitcommande ADD CONSTRAINT FK_FC570C6B4899E721 FOREIGN KEY (produitcommande_id) REFERENCES produit_commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_langue ADD CONSTRAINT FK_6405BEB4F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B36B274DD0 FOREIGN KEY (vendeurs_id) REFERENCES vendeur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3AB014612 FOREIGN KEY (clients_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE adresse ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F081619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_C35F081619EB6921 ON adresse (client_id)');
        $this->addSql('ALTER TABLE commande ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D19EB6921 ON commande (client_id)');
        $this->addSql('ALTER TABLE image ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FF347EFB ON image (produit_id)');
        $this->addSql('ALTER TABLE langue ADD produits_langues_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE langue ADD CONSTRAINT FK_9357758E2E68C17 FOREIGN KEY (produits_langues_id) REFERENCES produit_langue (id)');
        $this->addSql('CREATE INDEX IDX_9357758E2E68C17 ON langue (produits_langues_id)');
        $this->addSql('ALTER TABLE produit ADD promotion_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27139DF194 ON produit (promotion_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE langue DROP FOREIGN KEY FK_9357758E2E68C17');
        $this->addSql('ALTER TABLE commande_produitcommande DROP FOREIGN KEY FK_BA9AACBC82EA2E54');
        $this->addSql('ALTER TABLE commande_produitcommande DROP FOREIGN KEY FK_BA9AACBC4899E721');
        $this->addSql('ALTER TABLE produit_produitcommande DROP FOREIGN KEY FK_FC570C6BF347EFB');
        $this->addSql('ALTER TABLE produit_produitcommande DROP FOREIGN KEY FK_FC570C6B4899E721');
        $this->addSql('ALTER TABLE produit_langue DROP FOREIGN KEY FK_6405BEB4F347EFB');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B36B274DD0');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3AB014612');
        $this->addSql('DROP TABLE commande_produitcommande');
        $this->addSql('DROP TABLE produit_produitcommande');
        $this->addSql('DROP TABLE produit_langue');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F081619EB6921');
        $this->addSql('DROP INDEX IDX_C35F081619EB6921 ON adresse');
        $this->addSql('ALTER TABLE adresse DROP client_id');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('DROP INDEX IDX_6EEAA67D19EB6921 ON commande');
        $this->addSql('ALTER TABLE commande DROP client_id');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF347EFB');
        $this->addSql('DROP INDEX IDX_C53D045FF347EFB ON image');
        $this->addSql('ALTER TABLE image DROP produit_id');
        $this->addSql('DROP INDEX IDX_9357758E2E68C17 ON langue');
        $this->addSql('ALTER TABLE langue DROP produits_langues_id');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27139DF194');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('DROP INDEX IDX_29A5EC27139DF194 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC27BCF5E72D ON produit');
        $this->addSql('ALTER TABLE produit DROP promotion_id, DROP categorie_id');
    }
}
