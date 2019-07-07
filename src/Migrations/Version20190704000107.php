<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190704000107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe_ingredients_measure_unit (recipe_ingredients_id INT NOT NULL, measure_unit_id INT NOT NULL, INDEX IDX_6ABA8294717BDF5D (recipe_ingredients_id), INDEX IDX_6ABA829463C6A475 (measure_unit_id), PRIMARY KEY(recipe_ingredients_id, measure_unit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_ingredients_measure_unit ADD CONSTRAINT FK_6ABA8294717BDF5D FOREIGN KEY (recipe_ingredients_id) REFERENCES recipe_ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients_measure_unit ADD CONSTRAINT FK_6ABA829463C6A475 FOREIGN KEY (measure_unit_id) REFERENCES measure_unit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE recipe_ingredients_measure_unit');
    }
}
