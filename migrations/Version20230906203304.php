<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230906203304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recharge ADD utilise_par_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recharge ADD CONSTRAINT FK_B6702F51740320B9 FOREIGN KEY (utilise_par_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6702F51740320B9 ON recharge (utilise_par_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recharge DROP FOREIGN KEY FK_B6702F51740320B9');
        $this->addSql('DROP INDEX IDX_B6702F51740320B9 ON recharge');
        $this->addSql('ALTER TABLE recharge DROP utilise_par_id');
    }
}
