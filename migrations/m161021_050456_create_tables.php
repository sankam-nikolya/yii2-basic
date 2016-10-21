<?php

use yii\db\Schema;

class m161021_050456_create_tables extends yii\db\Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $this->checkTable('{{%auth_assignment}}');
        $this->createTable('{{%auth_assignment}}', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
        ]);
        $this->addPrimaryKey('PK', '{{%auth_assignment}}', ["item_name","user_id"] );


        $this->checkTable('{{%auth_item}}');
        $this->createTable('{{%auth_item}}', [
            'name' => $this->string(64)->notNull() . ' PRIMARY KEY',
            'type' => $this->integer()->notNull(),
            'description' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT'),
            'rule_name' => $this->string(64),
            'data' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex('rule_name', '{{%auth_item}}', 'rule_name', false);
        $this->createIndex('idx-auth_item-type', '{{%auth_item}}', 'type', false);


        $this->checkTable('{{%auth_item_child}}');
        $this->createTable('{{%auth_item_child}}', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
        ]);
        $this->addPrimaryKey('PK', '{{%auth_item_child}}', ["parent","child"] );

        $this->createIndex('child', '{{%auth_item_child}}', 'child', false);


        $this->checkTable('{{%auth_rule}}');
        $this->createTable('{{%auth_rule}}', [
            'name' => $this->string(64)->notNull() . ' PRIMARY KEY',
            'data' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);


        $this->checkTable('{{%file_storage_item}}');
        $this->createTable('{{%file_storage_item}}', [
            'id' => $this->primaryKey()->notNull(),
            'component' => $this->string(255)->notNull(),
            'base_url' => $this->string(1024)->notNull(),
            'path' => $this->string(1024)->notNull(),
            'type' => $this->string(255),
            'size' => $this->integer(),
            'name' => $this->string(255),
            'upload_ip' => $this->string(15),
            'created_at' => $this->integer()->notNull(),
            'optimized' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TINYINT(1)')->defaultValue('0'),
        ]);


        $this->checkTable('{{%i18n_message}}');
        $this->createTable('{{%i18n_message}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT'),
        ]);
        $this->addPrimaryKey('PK', '{{%i18n_message}}', ["id","language"] );


        $this->checkTable('{{%i18n_source_message}}');
        $this->createTable('{{%i18n_source_message}}', [
            'id' => $this->primaryKey()->notNull(),
            'category' => $this->string(32),
            'message' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT'),
        ]);


        $this->checkTable('{{%key_storage_item}}');
        $this->createTable('{{%key_storage_item}}', [
            'key' => $this->string(128)->notNull() . ' PRIMARY KEY',
            'value' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT')->notNull(),
            'comment' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT'),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ]);

        $this->createIndex('idx_key_storage_item_key', '{{%key_storage_item}}', 'key', true);


        $this->checkTable('{{%menu}}');
        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey()->notNull(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255),
            'icons' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TINYINT(1)')->defaultValue('0'),
            'max_levels' => $this->integer(3)->defaultValue('1'),
            'author_id' => $this->integer(),
            'updater_id' => $this->integer(),
            'created_at' => $this->integer(10),
            'updated_at' => $this->integer(10),
            'status' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TINYINT(1)')->defaultValue('0'),
        ]);

        $this->createIndex('IDX_MENU', '{{%menu}}', 'status', false);
        $this->createIndex('FK_MENU_AUTHOR', '{{%menu}}', 'author_id', false);
        $this->createIndex('FK_MENU_UPDATER', '{{%menu}}', 'updater_id', false);


        $this->checkTable('{{%menu_items}}');
        $this->createTable('{{%menu_items}}', [
            'id' => $this->primaryKey()->notNull(),
            'menu_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'link_type' => $this->integer(3)->notNull(),
            'title' => $this->string(255)->notNull(),
            'icon' => $this->string(255),
            'url' => $this->string(255),
            'show_child' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TINYINT(1)'),
            'visible' => $this->integer(3),
            'active' => $this->string(255),
            'target' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TINYINT(1)'),
            'order' => $this->integer()->defaultValue('0'),
            'level' => $this->integer(3)->defaultValue('1'),
            'author_id' => $this->integer(),
            'updater_id' => $this->integer(),
            'created_at' => $this->integer(10),
            'updated_at' => $this->integer(10),
            'status' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TINYINT(1)'),
        ]);

        $this->createIndex('FK_MENU_ITEM_AUTHOR', '{{%menu_items}}', 'author_id', false);
        $this->createIndex('FK_MENU_ITEM_PARENT', '{{%menu_items}}', 'parent_id', false);
        $this->createIndex('FK_MENU_ITEM_UPDATER', '{{%menu_items}}', 'updater_id', false);
        $this->createIndex('IDX_MENU_ITEM_STATUS', '{{%menu_items}}', 'status', false);
        $this->createIndex('FK_MENU_ITEM_MENU', '{{%menu_items}}', 'menu_id', false);
        $this->createIndex('IDX_MENU_ITEM_LEVEL', '{{%menu_items}}', 'level', false);


        $this->checkTable('{{%system_log}}');
        $this->createTable('{{%system_log}}', [
            'id' => $this->bigPrimaryKey()->notNull(),
            'level' => $this->integer(),
            'category' => $this->string(255),
            'log_time' => $this->double(),
            'prefix' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT'),
            'message' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TEXT'),
        ]);

        $this->createIndex('idx_log_level', '{{%system_log}}', 'level', false);
        $this->createIndex('idx_log_category', '{{%system_log}}', 'category', false);


        $this->checkTable('{{%tinypng_keys}}');
        $this->createTable('{{%tinypng_keys}}', [
            'id' => $this->primaryKey()->notNull(),
            'key' => $this->string(254)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'valid_from' => $this->integer(),
            'status' => $this->getDb()->getSchema()->createColumnSchemaBuilder('TINYINT(1)'),
        ]);


        $this->checkTable('{{%user}}');
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->notNull(),
            'username' => $this->string(32),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string(40)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'oauth_client' => $this->string(255),
            'oauth_client_user_id' => $this->string(255),
            'email' => $this->string(255)->notNull(),
            'status' => $this->getDb()->getSchema()->createColumnSchemaBuilder('SMALLINT(6)')->defaultValue('2')->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'logged_at' => $this->integer(),
        ]);


        $this->checkTable('{{%user_profile}}');
        $this->createTable('{{%user_profile}}', [
            'user_id' => $this->primaryKey()->notNull(),
            'firstname' => $this->string(255),
            'middlename' => $this->string(255),
            'lastname' => $this->string(255),
            'avatar_path' => $this->string(255),
            'avatar_base_url' => $this->string(255),
            'locale' => $this->string(32)->notNull(),
            'gender' => $this->getDb()->getSchema()->createColumnSchemaBuilder('SMALLINT(1)'),
        ]);


        $this->checkTable('{{%user_token}}');
        $this->createTable('{{%user_token}}', [
            'id' => $this->primaryKey()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->string(255)->notNull(),
            'token' => $this->string(40)->notNull(),
            'expire_at' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);


        $this->addForeignKey('auth_assignment_ibfk_1', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');


        $this->addForeignKey('auth_item_ibfk_1', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'SET NULL', 'CASCADE');


        $this->addForeignKey('auth_item_child_ibfk_2', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('auth_item_child_ibfk_1', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');




        $this->addForeignKey('fk_i18n_message_source_message', '{{%i18n_message}}', 'id', '{{%i18n_source_message}}', 'id', 'CASCADE', null);




        $this->addForeignKey('FK_MENU_UPDATER', '{{%menu}}', 'updater_id', '{{%user}}', 'id', 'SET NULL', null);
        $this->addForeignKey('FK_MENU_AUTHOR', '{{%menu}}', 'author_id', '{{%user}}', 'id', 'SET NULL', null);


        $this->addForeignKey('FK_MENU_ITEM_UPDATER', '{{%menu_items}}', 'updater_id', '{{%user}}', 'id', 'SET NULL', null);
        $this->addForeignKey('FK_MENU_ITEM_PARENT', '{{%menu_items}}', 'parent_id', '{{%menu_items}}', 'id', 'CASCADE', null);
        $this->addForeignKey('FK_MENU_ITEM_MENU', '{{%menu_items}}', 'menu_id', '{{%menu}}', 'id', 'CASCADE', null);
        $this->addForeignKey('FK_MENU_ITEM_AUTHOR', '{{%menu_items}}', 'author_id', '{{%user}}', 'id', 'SET NULL', null);





        $this->addForeignKey('fk_user', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');



        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $this->dropTable('{{%user_token}}');
        $this->dropTable('{{%user_profile}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%tinypng_keys}}');
        $this->dropTable('{{%system_log}}');
        $this->dropTable('{{%menu_items}}');
        $this->dropTable('{{%menu}}');
        $this->dropTable('{{%key_storage_item}}');
        $this->dropTable('{{%i18n_source_message}}');
        $this->dropTable('{{%i18n_message}}');
        $this->dropTable('{{%file_storage_item}}');
        $this->dropTable('{{%auth_rule}}');
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_item}}');
        $this->dropTable('{{%auth_assignment}}');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    private function checkTable($table) {
        if ($this->getDb()->getSchema()->getTableSchema($table, true) != null) {
            $this->dropTable($table);
        }
    }
}
