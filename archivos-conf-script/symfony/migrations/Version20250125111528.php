<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250125111528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comentarios (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, publicacion_id INT NOT NULL, comentario_resp_id INT DEFAULT NULL, contenido_com VARCHAR(255) NOT NULL, INDEX IDX_F54B3FC0DB38439E (usuario_id), INDEX IDX_F54B3FC09ACBB5E7 (publicacion_id), INDEX IDX_F54B3FC0FB9C7413 (comentario_resp_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publicaciones (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, contenido VARCHAR(255) NOT NULL, fecha_pub DATETIME NOT NULL, INDEX IDX_A3A706C0DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comentarios ADD CONSTRAINT FK_F54B3FC0DB38439E FOREIGN KEY (usuario_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comentarios ADD CONSTRAINT FK_F54B3FC09ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicaciones (id)');
        $this->addSql('ALTER TABLE comentarios ADD CONSTRAINT FK_F54B3FC0FB9C7413 FOREIGN KEY (comentario_resp_id) REFERENCES comentarios (id)');
        $this->addSql('ALTER TABLE publicaciones ADD CONSTRAINT FK_A3A706C0DB38439E FOREIGN KEY (usuario_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentarios DROP FOREIGN KEY FK_F54B3FC0DB38439E');
        $this->addSql('ALTER TABLE comentarios DROP FOREIGN KEY FK_F54B3FC09ACBB5E7');
        $this->addSql('ALTER TABLE comentarios DROP FOREIGN KEY FK_F54B3FC0FB9C7413');
        $this->addSql('ALTER TABLE publicaciones DROP FOREIGN KEY FK_A3A706C0DB38439E');
        $this->addSql('DROP TABLE comentarios');
        $this->addSql('DROP TABLE publicaciones');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
