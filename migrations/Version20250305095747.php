<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305095747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_read (id INT AUTO_INCREMENT NOT NULL, read_by TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_read_message (message_read_id INT NOT NULL, message_id INT NOT NULL, INDEX IDX_CE5B6715A73C9812 (message_read_id), INDEX IDX_CE5B6715537A1329 (message_id), PRIMARY KEY(message_read_id, message_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_read_user (message_read_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_62DE0C46A73C9812 (message_read_id), INDEX IDX_62DE0C46A76ED395 (user_id), PRIMARY KEY(message_read_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_read_message ADD CONSTRAINT FK_CE5B6715A73C9812 FOREIGN KEY (message_read_id) REFERENCES message_read (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_read_message ADD CONSTRAINT FK_CE5B6715537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_read_user ADD CONSTRAINT FK_62DE0C46A73C9812 FOREIGN KEY (message_read_id) REFERENCES message_read (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_read_user ADD CONSTRAINT FK_62DE0C46A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_read_message DROP FOREIGN KEY FK_CE5B6715A73C9812');
        $this->addSql('ALTER TABLE message_read_message DROP FOREIGN KEY FK_CE5B6715537A1329');
        $this->addSql('ALTER TABLE message_read_user DROP FOREIGN KEY FK_62DE0C46A73C9812');
        $this->addSql('ALTER TABLE message_read_user DROP FOREIGN KEY FK_62DE0C46A76ED395');
        $this->addSql('DROP TABLE message_read');
        $this->addSql('DROP TABLE message_read_message');
        $this->addSql('DROP TABLE message_read_user');
    }
}
