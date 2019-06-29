<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628212424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo_album ADD cover_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photo_album ADD CONSTRAINT FK_83C969F4E5A0E336 FOREIGN KEY (cover_image_id) REFERENCES photo (id)');
        $this->addSql('CREATE INDEX IDX_83C969F4E5A0E336 ON photo_album (cover_image_id)');
        $this->addSql('ALTER TABLE photo_gallery ADD cover_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photo_gallery ADD CONSTRAINT FK_72CB6FB7E5A0E336 FOREIGN KEY (cover_image_id) REFERENCES photo (id)');
        $this->addSql('CREATE INDEX IDX_72CB6FB7E5A0E336 ON photo_gallery (cover_image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo_album DROP FOREIGN KEY FK_83C969F4E5A0E336');
        $this->addSql('DROP INDEX IDX_83C969F4E5A0E336 ON photo_album');
        $this->addSql('ALTER TABLE photo_album DROP cover_image_id');
        $this->addSql('ALTER TABLE photo_gallery DROP FOREIGN KEY FK_72CB6FB7E5A0E336');
        $this->addSql('DROP INDEX IDX_72CB6FB7E5A0E336 ON photo_gallery');
        $this->addSql('ALTER TABLE photo_gallery DROP cover_image_id');
    }
}
