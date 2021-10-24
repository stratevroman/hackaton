<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211024112537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE audio_text_detail_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE audio_text_detail_bad_word_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE audio_text_detail (id INT NOT NULL, audio_text_id INT NOT NULL, text VARCHAR(255) NOT NULL, offset_at_from_start_text INT NOT NULL, number_of_characters INT NOT NULL, start_at INT NOT NULL, end_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_453BCE8C563A3C94 ON audio_text_detail (audio_text_id)');
        $this->addSql('CREATE TABLE audio_text_detail_bad_word (id INT NOT NULL, audio_text_detail_id INT NOT NULL, index INT NOT NULL, word VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25C209EC6A1C3475 ON audio_text_detail_bad_word (audio_text_detail_id)');
        $this->addSql('ALTER TABLE audio_text_detail ADD CONSTRAINT FK_453BCE8C563A3C94 FOREIGN KEY (audio_text_id) REFERENCES audio_text (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE audio_text_detail_bad_word ADD CONSTRAINT FK_25C209EC6A1C3475 FOREIGN KEY (audio_text_detail_id) REFERENCES audio_text_detail (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE audio_text DROP CONSTRAINT fk_743abb9b1dd63294');
        $this->addSql('DROP INDEX uniq_743abb9b1dd63294');
        $this->addSql('ALTER TABLE audio_text ALTER body SET NOT NULL');
        $this->addSql('ALTER TABLE audio_text RENAME COLUMN audio_id_id TO audio_id');
        $this->addSql('ALTER TABLE audio_text ADD CONSTRAINT FK_743ABB9B3A3123C7 FOREIGN KEY (audio_id) REFERENCES audio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_743ABB9B3A3123C7 ON audio_text (audio_id)');
        $this->addSql('ALTER TABLE "group" ADD audio_text_detail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C56A1C3475 FOREIGN KEY (audio_text_detail_id) REFERENCES audio_text_detail (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6DC044C56A1C3475 ON "group" (audio_text_detail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE audio_text_detail_bad_word DROP CONSTRAINT FK_25C209EC6A1C3475');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C56A1C3475');
        $this->addSql('DROP SEQUENCE audio_text_detail_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE audio_text_detail_bad_word_id_seq CASCADE');
        $this->addSql('DROP TABLE audio_text_detail');
        $this->addSql('DROP TABLE audio_text_detail_bad_word');
        $this->addSql('ALTER TABLE audio_text DROP CONSTRAINT FK_743ABB9B3A3123C7');
        $this->addSql('DROP INDEX UNIQ_743ABB9B3A3123C7');
        $this->addSql('ALTER TABLE audio_text ALTER body DROP NOT NULL');
        $this->addSql('ALTER TABLE audio_text RENAME COLUMN audio_id TO audio_id_id');
        $this->addSql('ALTER TABLE audio_text ADD CONSTRAINT fk_743abb9b1dd63294 FOREIGN KEY (audio_id_id) REFERENCES audio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_743abb9b1dd63294 ON audio_text (audio_id_id)');
        $this->addSql('DROP INDEX IDX_6DC044C56A1C3475');
        $this->addSql('ALTER TABLE "group" DROP audio_text_detail_id');
    }
}
