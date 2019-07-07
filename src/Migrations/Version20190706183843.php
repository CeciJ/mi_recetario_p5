<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190706183843 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe_recipe_ingredients (recipe_id INT NOT NULL, recipe_ingredients_id INT NOT NULL, INDEX IDX_4EFB713259D8A214 (recipe_id), INDEX IDX_4EFB7132717BDF5D (recipe_ingredients_id), PRIMARY KEY(recipe_id, recipe_ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_recipe_ingredients ADD CONSTRAINT FK_4EFB713259D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_recipe_ingredients ADD CONSTRAINT FK_4EFB7132717BDF5D FOREIGN KEY (recipe_ingredients_id) REFERENCES recipe_ingredients (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE recipe_ingredients_recipe');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe_ingredients_recipe (recipe_ingredients_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_5D3C0472717BDF5D (recipe_ingredients_id), INDEX IDX_5D3C047259D8A214 (recipe_id), PRIMARY KEY(recipe_ingredients_id, recipe_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recipe_ingredients_recipe ADD CONSTRAINT FK_5D3C047259D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients_recipe ADD CONSTRAINT FK_5D3C0472717BDF5D FOREIGN KEY (recipe_ingredients_id) REFERENCES recipe_ingredients (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE recipe_recipe_ingredients');
    }
}
