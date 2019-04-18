<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190331013415 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT NOT NULL, title LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', content LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', summary LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', image VARCHAR(255) NOT NULL, publish_time DATETIME NOT NULL, visible TINYINT(1) NOT NULL, INDEX IDX_BA5AE01D12469DE2 (category_id), INDEX IDX_BA5AE01DF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post_blog_tag (blog_post_id INT NOT NULL, blog_tag_id INT NOT NULL, INDEX IDX_CA877A44A77FBEAF (blog_post_id), INDEX IDX_CA877A442F9DC6D0 (blog_tag_id), PRIMARY KEY(blog_post_id, blog_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_category (id INT AUTO_INCREMENT NOT NULL, language_key VARCHAR(255) NOT NULL, url_name VARCHAR(255) NOT NULL, description_language_key VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_tag (id INT AUTO_INCREMENT NOT NULL, language_key VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, public_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D12469DE2 FOREIGN KEY (category_id) REFERENCES blog_category (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog_post_blog_tag ADD CONSTRAINT FK_CA877A44A77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_post_blog_tag ADD CONSTRAINT FK_CA877A442F9DC6D0 FOREIGN KEY (blog_tag_id) REFERENCES blog_tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_post_blog_tag DROP FOREIGN KEY FK_CA877A44A77FBEAF');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE blog_post_blog_tag DROP FOREIGN KEY FK_CA877A442F9DC6D0');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DF675F31B');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE blog_post_blog_tag');
        $this->addSql('DROP TABLE blog_category');
        $this->addSql('DROP TABLE blog_tag');
        $this->addSql('DROP TABLE user');
    }
}
