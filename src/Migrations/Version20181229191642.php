<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181229191642 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, logo VARCHAR(255) DEFAULT NULL, name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection_banner (id INT AUTO_INCREMENT NOT NULL, collection_item_id INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, media_height VARCHAR(10) DEFAULT NULL, media_width VARCHAR(10) DEFAULT NULL, original_name VARCHAR(255) DEFAULT NULL, is_online TINYINT(1) DEFAULT NULL, date_published DATETIME DEFAULT NULL, date_end DATETIME DEFAULT NULL, INDEX IDX_9DB840114643208F (collection_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection_item (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) DEFAULT NULL, position INT NOT NULL, collection_name VARCHAR(255) DEFAULT NULL, date_published DATETIME DEFAULT NULL, alterntive_name VARCHAR(255) DEFAULT NULL, has_discount TINYINT(1) DEFAULT NULL, discount INT DEFAULT NULL, is_carousel TINYINT(1) DEFAULT NULL, has_banner TINYINT(1) DEFAULT NULL, content_media_banner VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, comment_text LONGTEXT DEFAULT NULL, comment_time DATETIME DEFAULT NULL, is_online TINYINT(1) DEFAULT NULL, is_all_user_view_online TINYINT(1) DEFAULT NULL, date_published DATETIME DEFAULT NULL, INDEX IDX_9474526C61220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, media_height VARCHAR(255) DEFAULT NULL, media_width VARCHAR(255) DEFAULT NULL, original_name VARCHAR(255) DEFAULT NULL, is_online TINYINT(1) DEFAULT NULL, has_in_one TINYINT(1) DEFAULT NULL, date_published DATETIME DEFAULT NULL, content_url VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(100) DEFAULT NULL, INDEX IDX_6A2CA10C126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, availability VARCHAR(100) DEFAULT NULL, is_availability TINYINT(1) DEFAULT NULL, price INT NOT NULL, buy_price INT NOT NULL, is_active_price TINYINT(1) DEFAULT NULL, date_published DATETIME DEFAULT NULL, INDEX IDX_29D6873E126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, payment_method_id INT DEFAULT NULL, billing_address LONGTEXT DEFAULT NULL, order_date DATETIME DEFAULT NULL, order_number VARCHAR(20) DEFAULT NULL, order_status TINYINT(1) DEFAULT NULL, order_delivery VARCHAR(100) DEFAULT NULL, is_gift TINYINT(1) DEFAULT NULL, INDEX IDX_F52993989395C3F3 (customer_id), INDEX IDX_F52993985AA1164F (payment_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, ordered_item_id INT DEFAULT NULL, quantitative_value INT NOT NULL, has_discount TINYINT(1) DEFAULT NULL, discount INT DEFAULT NULL, price INT DEFAULT NULL, date_published DATETIME DEFAULT NULL, INDEX IDX_2530ADE6126F525E (item_id), INDEX IDX_2530ADE627D5C145 (ordered_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, payment_url VARCHAR(255) DEFAULT NULL, name VARCHAR(100) DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, sub_category_id INT DEFAULT NULL, brand_id INT DEFAULT NULL, production_date DATE DEFAULT NULL, sku VARCHAR(255) DEFAULT NULL, weight VARCHAR(100) DEFAULT NULL, width VARCHAR(100) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, date_published DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D34A04ADF9038C4 (sku), INDEX IDX_D34A04ADF7BFE87C (sub_category_id), INDEX IDX_D34A04AD44F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_collection_item (product_id INT NOT NULL, collection_item_id INT NOT NULL, INDEX IDX_67E70C6D4584665A (product_id), INDEX IDX_67E70C6D4643208F (collection_item_id), PRIMARY KEY(product_id, collection_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(128) NOT NULL, telephone VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', additional_name VARCHAR(255) DEFAULT NULL, family_name VARCHAR(128) DEFAULT NULL, birth_date DATETIME DEFAULT NULL, gender VARCHAR(12) DEFAULT NULL, address LONGTEXT DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, created DATETIME DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C2502824E7927C74 (email), UNIQUE INDEX UNIQ_C2502824F85E0677 (username), UNIQUE INDEX UNIQ_C2502824450FF010 (telephone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collection_banner ADD CONSTRAINT FK_9DB840114643208F FOREIGN KEY (collection_item_id) REFERENCES collection_item (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C61220EA6 FOREIGN KEY (creator_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C126F525E FOREIGN KEY (item_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E126F525E FOREIGN KEY (item_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993985AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6126F525E FOREIGN KEY (item_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE627D5C145 FOREIGN KEY (ordered_item_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE product_collection_item ADD CONSTRAINT FK_67E70C6D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_collection_item ADD CONSTRAINT FK_67E70C6D4643208F FOREIGN KEY (collection_item_id) REFERENCES collection_item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE collection_banner DROP FOREIGN KEY FK_9DB840114643208F');
        $this->addSql('ALTER TABLE product_collection_item DROP FOREIGN KEY FK_67E70C6D4643208F');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE627D5C145');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993985AA1164F');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C126F525E');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E126F525E');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6126F525E');
        $this->addSql('ALTER TABLE product_collection_item DROP FOREIGN KEY FK_67E70C6D4584665A');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C61220EA6');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989395C3F3');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE collection_banner');
        $this->addSql('DROP TABLE collection_item');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_collection_item');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
