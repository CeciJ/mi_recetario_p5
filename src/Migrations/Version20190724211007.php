<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190724211007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE recipe_ingredients_measure_unit');
        $this->addSql('DROP TABLE recipe_ingredients_recipe');
        $this->addSql('ALTER TABLE meal_planning ADD CONSTRAINT FK_B08BA48459D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_B08BA48459D8A214 ON meal_planning (recipe_id)');
        $this->addSql('ALTER TABLE measure_unit CHANGE name unit VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients ADD unit_id INT NOT NULL, ADD recipe_id INT NOT NULL, CHANGE quantity quantity INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2BF8BD700D FOREIGN KEY (unit_id) REFERENCES measure_unit (id)');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_9F925F2BF8BD700D ON recipe_ingredients (unit_id)');
        $this->addSql('CREATE INDEX IDX_9F925F2B59D8A214 ON recipe_ingredients (recipe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe_ingredients_measure_unit (recipe_ingredients_id INT NOT NULL, measure_unit_id INT NOT NULL, INDEX IDX_6ABA8294717BDF5D (recipe_ingredients_id), INDEX IDX_6ABA829463C6A475 (measure_unit_id), PRIMARY KEY(recipe_ingredients_id, measure_unit_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE recipe_ingredients_recipe (recipe_ingredients_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_5D3C0472717BDF5D (recipe_ingredients_id), INDEX IDX_5D3C047259D8A214 (recipe_id), PRIMARY KEY(recipe_ingredients_id, recipe_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recipe_ingredients_measure_unit ADD CONSTRAINT FK_6ABA829463C6A475 FOREIGN KEY (measure_unit_id) REFERENCES measure_unit (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients_measure_unit ADD CONSTRAINT FK_6ABA8294717BDF5D FOREIGN KEY (recipe_ingredients_id) REFERENCES recipe_ingredients (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients_recipe ADD CONSTRAINT FK_5D3C047259D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients_recipe ADD CONSTRAINT FK_5D3C0472717BDF5D FOREIGN KEY (recipe_ingredients_id) REFERENCES recipe_ingredients (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meal_planning DROP FOREIGN KEY FK_B08BA48459D8A214');
        $this->addSql('DROP INDEX IDX_B08BA48459D8A214 ON meal_planning');
        $this->addSql('ALTER TABLE measure_unit CHANGE unit name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2BF8BD700D');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B59D8A214');
        $this->addSql('DROP INDEX IDX_9F925F2BF8BD700D ON recipe_ingredients');
        $this->addSql('DROP INDEX IDX_9F925F2B59D8A214 ON recipe_ingredients');
        $this->addSql('ALTER TABLE recipe_ingredients DROP unit_id, DROP recipe_id, CHANGE quantity quantity DOUBLE PRECISION NOT NULL');
    }
}
