<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250805123747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_category_recipe (recipe_category_id INT NOT NULL, recipe_id INT NOT NULL, PRIMARY KEY(recipe_category_id, recipe_id))');
        $this->addSql('CREATE INDEX IDX_BC142E20C6B4D386 ON recipe_category_recipe (recipe_category_id)');
        $this->addSql('CREATE INDEX IDX_BC142E2059D8A214 ON recipe_category_recipe (recipe_id)');
        $this->addSql('ALTER TABLE recipe_category_recipe ADD CONSTRAINT FK_BC142E20C6B4D386 FOREIGN KEY (recipe_category_id) REFERENCES recipe_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe_category_recipe ADD CONSTRAINT FK_BC142E2059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE recipe_category_recipe DROP CONSTRAINT FK_BC142E20C6B4D386');
        $this->addSql('ALTER TABLE recipe_category_recipe DROP CONSTRAINT FK_BC142E2059D8A214');
        $this->addSql('DROP TABLE recipe_category_recipe');
    }
}
