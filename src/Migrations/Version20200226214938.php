<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200226214938 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE measure_unit (id INT AUTO_INCREMENT NOT NULL, unit VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE corresponding_weights_unities (id INT AUTO_INCREMENT NOT NULL, ingredient VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal_planning (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, begin_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, end_at DATETIME DEFAULT NULL, INDEX IDX_B08BA48459D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_16DB4F8959D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, cooking_time SMALLINT NOT NULL, cost SMALLINT NOT NULL, created_at DATETIME NOT NULL, difficulty SMALLINT NOT NULL, number_persons SMALLINT NOT NULL, preparation_time SMALLINT NOT NULL, total_time SMALLINT NOT NULL, updated_at DATETIME DEFAULT NULL, steps LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_option (recipe_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_D7981E9159D8A214 (recipe_id), INDEX IDX_D7981E91A7C41D6F (option_id), PRIMARY KEY(recipe_id, option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_dish_type (recipe_id INT NOT NULL, dish_type_id INT NOT NULL, INDEX IDX_111DC6DE59D8A214 (recipe_id), INDEX IDX_111DC6DE55FB9605 (dish_type_id), PRIMARY KEY(recipe_id, dish_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_food_type (recipe_id INT NOT NULL, food_type_id INT NOT NULL, INDEX IDX_2E38D11E59D8A214 (recipe_id), INDEX IDX_2E38D11E8AD350AB (food_type_id), PRIMARY KEY(recipe_id, food_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_ingredients (id INT AUTO_INCREMENT NOT NULL, unit_id INT NOT NULL, name_ingredient_id INT NOT NULL, recipe_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_9F925F2BF8BD700D (unit_id), INDEX IDX_9F925F2BC1D8640A (name_ingredient_id), INDEX IDX_9F925F2B59D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meal_planning ADD CONSTRAINT FK_B08BA48459D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_option ADD CONSTRAINT FK_D7981E9159D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_option ADD CONSTRAINT FK_D7981E91A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_dish_type ADD CONSTRAINT FK_111DC6DE59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_dish_type ADD CONSTRAINT FK_111DC6DE55FB9605 FOREIGN KEY (dish_type_id) REFERENCES dish_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_food_type ADD CONSTRAINT FK_2E38D11E59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_food_type ADD CONSTRAINT FK_2E38D11E8AD350AB FOREIGN KEY (food_type_id) REFERENCES food_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2BF8BD700D FOREIGN KEY (unit_id) REFERENCES measure_unit (id)');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2BC1D8640A FOREIGN KEY (name_ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2BC1D8640A');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2BF8BD700D');
        $this->addSql('ALTER TABLE recipe_dish_type DROP FOREIGN KEY FK_111DC6DE55FB9605');
        $this->addSql('ALTER TABLE recipe_food_type DROP FOREIGN KEY FK_2E38D11E8AD350AB');
        $this->addSql('ALTER TABLE recipe_option DROP FOREIGN KEY FK_D7981E91A7C41D6F');
        $this->addSql('ALTER TABLE meal_planning DROP FOREIGN KEY FK_B08BA48459D8A214');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8959D8A214');
        $this->addSql('ALTER TABLE recipe_option DROP FOREIGN KEY FK_D7981E9159D8A214');
        $this->addSql('ALTER TABLE recipe_dish_type DROP FOREIGN KEY FK_111DC6DE59D8A214');
        $this->addSql('ALTER TABLE recipe_food_type DROP FOREIGN KEY FK_2E38D11E59D8A214');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B59D8A214');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE measure_unit');
        $this->addSql('DROP TABLE corresponding_weights_unities');
        $this->addSql('DROP TABLE dish_type');
        $this->addSql('DROP TABLE food_type');
        $this->addSql('DROP TABLE meal_planning');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_option');
        $this->addSql('DROP TABLE recipe_dish_type');
        $this->addSql('DROP TABLE recipe_food_type');
        $this->addSql('DROP TABLE recipe_ingredients');
    }
}
