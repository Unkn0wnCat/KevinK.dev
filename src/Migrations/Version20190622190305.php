<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190622190305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', description LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', src VARCHAR(255) NOT NULL, gps_location LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', capture_timestamp DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_album (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', description LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', content_cards LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_album_project (photo_album_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_FE55A72B820BB445 (photo_album_id), INDEX IDX_FE55A72B166D1F9C (project_id), PRIMARY KEY(photo_album_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_album_photo (photo_album_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_6C9822E2820BB445 (photo_album_id), INDEX IDX_6C9822E27E9E4C8C (photo_id), PRIMARY KEY(photo_album_id, photo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_album_blog_post (photo_album_id INT NOT NULL, blog_post_id INT NOT NULL, INDEX IDX_C2B6F66F820BB445 (photo_album_id), INDEX IDX_C2B6F66FA77FBEAF (blog_post_id), PRIMARY KEY(photo_album_id, blog_post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_gallery (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', description LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', directly_show_photos TINYINT(1) NOT NULL, listed TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_gallery_photo (photo_gallery_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_741B7EEC48834187 (photo_gallery_id), INDEX IDX_741B7EEC7E9E4C8C (photo_id), PRIMARY KEY(photo_gallery_id, photo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_gallery_photo_album (photo_gallery_id INT NOT NULL, photo_album_id INT NOT NULL, INDEX IDX_4024179A48834187 (photo_gallery_id), INDEX IDX_4024179A820BB445 (photo_album_id), PRIMARY KEY(photo_gallery_id, photo_album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photo_album_project ADD CONSTRAINT FK_FE55A72B820BB445 FOREIGN KEY (photo_album_id) REFERENCES photo_album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_album_project ADD CONSTRAINT FK_FE55A72B166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_album_photo ADD CONSTRAINT FK_6C9822E2820BB445 FOREIGN KEY (photo_album_id) REFERENCES photo_album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_album_photo ADD CONSTRAINT FK_6C9822E27E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_album_blog_post ADD CONSTRAINT FK_C2B6F66F820BB445 FOREIGN KEY (photo_album_id) REFERENCES photo_album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_album_blog_post ADD CONSTRAINT FK_C2B6F66FA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_gallery_photo ADD CONSTRAINT FK_741B7EEC48834187 FOREIGN KEY (photo_gallery_id) REFERENCES photo_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_gallery_photo ADD CONSTRAINT FK_741B7EEC7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_gallery_photo_album ADD CONSTRAINT FK_4024179A48834187 FOREIGN KEY (photo_gallery_id) REFERENCES photo_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_gallery_photo_album ADD CONSTRAINT FK_4024179A820BB445 FOREIGN KEY (photo_album_id) REFERENCES photo_album (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo_album_photo DROP FOREIGN KEY FK_6C9822E27E9E4C8C');
        $this->addSql('ALTER TABLE photo_gallery_photo DROP FOREIGN KEY FK_741B7EEC7E9E4C8C');
        $this->addSql('ALTER TABLE photo_album_project DROP FOREIGN KEY FK_FE55A72B820BB445');
        $this->addSql('ALTER TABLE photo_album_photo DROP FOREIGN KEY FK_6C9822E2820BB445');
        $this->addSql('ALTER TABLE photo_album_blog_post DROP FOREIGN KEY FK_C2B6F66F820BB445');
        $this->addSql('ALTER TABLE photo_gallery_photo_album DROP FOREIGN KEY FK_4024179A820BB445');
        $this->addSql('ALTER TABLE photo_gallery_photo DROP FOREIGN KEY FK_741B7EEC48834187');
        $this->addSql('ALTER TABLE photo_gallery_photo_album DROP FOREIGN KEY FK_4024179A48834187');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE photo_album');
        $this->addSql('DROP TABLE photo_album_project');
        $this->addSql('DROP TABLE photo_album_photo');
        $this->addSql('DROP TABLE photo_album_blog_post');
        $this->addSql('DROP TABLE photo_gallery');
        $this->addSql('DROP TABLE photo_gallery_photo');
        $this->addSql('DROP TABLE photo_gallery_photo_album');
    }
}
