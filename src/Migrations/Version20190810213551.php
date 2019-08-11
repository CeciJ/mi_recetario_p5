<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190810213551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE recipe_ingredients_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredients ADD name_ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2BF8BD700D FOREIGN KEY (unit_id) REFERENCES measure_unit (id)');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2BC1D8640A FOREIGN KEY (name_ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('CREATE INDEX IDX_9F925F2BF8BD700D ON recipe_ingredients (unit_id)');
        $this->addSql('CREATE INDEX IDX_9F925F2B59D8A214 ON recipe_ingredients (recipe_id)');
        $this->addSql('CREATE INDEX IDX_9F925F2BC1D8640A ON recipe_ingredients (name_ingredient_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe_ingredients_ingredient (recipe_ingredients_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_F78591BB933FE08C (ingredient_id), INDEX IDX_F78591BB717BDF5D (recipe_ingredients_id), PRIMARY KEY(recipe_ingredients_id, ingredient_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recipe_ingredients_ingredient ADD CONSTRAINT FK_F78591BB717BDF5D FOREIGN KEY (recipe_ingredients_id) REFERENCES recipe_ingredients (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients_ingredient ADD CONSTRAINT FK_F78591BB933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2BF8BD700D');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B59D8A214');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2BC1D8640A');
        $this->addSql('DROP INDEX IDX_9F925F2BF8BD700D ON recipe_ingredients');
        $this->addSql('DROP INDEX IDX_9F925F2B59D8A214 ON recipe_ingredients');
        $this->addSql('DROP INDEX IDX_9F925F2BC1D8640A ON recipe_ingredients');
        $this->addSql('ALTER TABLE recipe_ingredients DROP name_ingredient_id');
    }
}