<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310071341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commantaire_publication ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commantaire_publication ADD CONSTRAINT FK_AB54DB89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB54DB89A76ED395 ON commantaire_publication (user_id)');
        $this->addSql('ALTER TABLE publication ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779A76ED395 ON publication (user_id)');
        $this->addSql('ALTER TABLE reaction_publication ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reaction_publication ADD CONSTRAINT FK_EC2A804FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EC2A804FA76ED395 ON reaction_publication (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commantaire_publication DROP FOREIGN KEY FK_AB54DB89A76ED395');
        $this->addSql('DROP INDEX IDX_AB54DB89A76ED395 ON commantaire_publication');
        $this->addSql('ALTER TABLE commantaire_publication DROP user_id');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A76ED395');
        $this->addSql('DROP INDEX IDX_AF3C6779A76ED395 ON publication');
        $this->addSql('ALTER TABLE publication DROP user_id');
        $this->addSql('ALTER TABLE reaction_publication DROP FOREIGN KEY FK_EC2A804FA76ED395');
        $this->addSql('DROP INDEX IDX_EC2A804FA76ED395 ON reaction_publication');
        $this->addSql('ALTER TABLE reaction_publication DROP user_id');
    }
}
