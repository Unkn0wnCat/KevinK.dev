<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628205722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE photo_gallery_photo');
        $this->addSql('ALTER TABLE photo_gallery DROP directly_show_photos');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE photo_gallery_photo (photo_gallery_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_741B7EEC7E9E4C8C (photo_id), INDEX IDX_741B7EEC48834187 (photo_gallery_id), PRIMARY KEY(photo_gallery_id, photo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE photo_gallery_photo ADD CONSTRAINT FK_741B7EEC48834187 FOREIGN KEY (photo_gallery_id) REFERENCES photo_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_gallery_photo ADD CONSTRAINT FK_741B7EEC7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_gallery ADD directly_show_photos TINYINT(1) NOT NULL');
    }
}
