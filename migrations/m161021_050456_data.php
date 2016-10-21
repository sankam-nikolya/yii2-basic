<?php

use yii\db\Schema;

class m161021_050456_data extends yii\db\Migration
{
    public function safeUp()
    {
        # {{%auth_assignment}}
        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'administrator',
            'user_id' => '1',
            'created_at' => '1476881378',
        ]);

        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'manager',
            'user_id' => '2',
            'created_at' => '1476913288',
        ]);

        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'user',
            'user_id' => '3',
            'created_at' => '1459770507',
        ]);


        # {{%auth_item}}
        $this->insert('{{%auth_item}}', [
            'name' => 'administrator',
            'type' => '1',
            'description' => 'NULL',
            'rule_name' => 'NULL',
            'data' => 'NULL',
            'created_at' => '1459770507',
            'updated_at' => '1459770507',
        ]);

        $this->insert('{{%auth_item}}', [
            'name' => 'editOwnModel',
            'type' => '2',
            'description' => 'NULL',
            'rule_name' => 'ownModelRule',
            'data' => 'NULL',
            'created_at' => '1459770507',
            'updated_at' => '1459770507',
        ]);

        $this->insert('{{%auth_item}}', [
            'name' => 'loginToBackend',
            'type' => '2',
            'description' => 'NULL',
            'rule_name' => 'NULL',
            'data' => 'NULL',
            'created_at' => '1459770507',
            'updated_at' => '1459770507',
        ]);

        $this->insert('{{%auth_item}}', [
            'name' => 'manager',
            'type' => '1',
            'description' => 'NULL',
            'rule_name' => 'NULL',
            'data' => 'NULL',
            'created_at' => '1459770506',
            'updated_at' => '1459770506',
        ]);

        $this->insert('{{%auth_item}}', [
            'name' => 'user',
            'type' => '1',
            'description' => 'NULL',
            'rule_name' => 'NULL',
            'data' => 'NULL',
            'created_at' => '1459770506',
            'updated_at' => '1459770506',
        ]);


        # {{%auth_item_child}}
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'user',
            'child' => 'editOwnModel',
        ]);

        $this->insert('{{%auth_item_child}}', [
            'parent' => 'manager',
            'child' => 'loginToBackend',
        ]);

        $this->insert('{{%auth_item_child}}', [
            'parent' => 'administrator',
            'child' => 'manager',
        ]);

        $this->insert('{{%auth_item_child}}', [
            'parent' => 'manager',
            'child' => 'user',
        ]);


        # {{%auth_rule}}
        $this->insert('{{%auth_rule}}', [
            'name' => 'ownModelRule',
            'data' => 'O:29:"common\rbac\rule\OwnModelRule":3:{s:4:"name";s:12:"ownModelRule";s:9:"createdAt";i:1459770507;s:9:"updatedAt";i:1459770507;}',
            'created_at' => '1459770507',
            'updated_at' => '1459770507',
        ]);


        # {{%file_storage_item}}
        $this->insert('{{%file_storage_item}}', [
            'id' => '2',
            'component' => 'fileStorage',
            'base_url' => '/uploads',
            'path' => '1/J_KKATvdGWwRu9T2lOYW9Ci9dcUxQMoy.png',
            'type' => 'image/png',
            'size' => '13075',
            'name' => 'J_KKATvdGWwRu9T2lOYW9Ci9dcUxQMoy',
            'upload_ip' => '127.0.0.1',
            'created_at' => '1476871688',
            'optimized' => '1',
        ]);


        # {{%key_storage_item}}
        $this->insert('{{%key_storage_item}}', [
            'key' => 'app.company',
            'value' => 'SANKAM',
            'comment' => 'NULL',
            'updated_at' => '1476874415',
            'created_at' => '1476874415',
        ]);

        $this->insert('{{%key_storage_item}}', [
            'key' => 'app.name',
            'value' => 'Site Name',
            'comment' => 'NULL',
            'updated_at' => '1476874449',
            'created_at' => 'NULL',
        ]);

        $this->insert('{{%key_storage_item}}', [
            'key' => 'backend.avatar.thumb',
            'value' => '20',
            'comment' => 'NULL',
            'updated_at' => 'NULL',
            'created_at' => 'NULL',
        ]);

        $this->insert('{{%key_storage_item}}', [
            'key' => 'backend.list.thumb',
            'value' => '30',
            'comment' => 'NULL',
            'updated_at' => 'NULL',
            'created_at' => 'NULL',
        ]);

        $this->insert('{{%key_storage_item}}', [
            'key' => 'frontend.maintenance',
            'value' => 'disabled',
            'comment' => 'Set it to "true" to turn on maintenance mode',
            'updated_at' => 'NULL',
            'created_at' => 'NULL',
        ]);

        $this->insert('{{%key_storage_item}}', [
            'key' => 'frontend.widget.cachetime',
            'value' => '86400',
            'comment' => 'NULL',
            'updated_at' => 'NULL',
            'created_at' => 'NULL',
        ]);


        # {{%menu}}
        $this->insert('{{%menu}}', [
            'id' => '1',
            'title' => 'Верхнее меню',
            'slug' => 'verhnee-menu',
            'icons' => '0',
            'max_levels' => '2',
            'author_id' => '1',
            'updater_id' => '1',
            'created_at' => '1476978633',
            'updated_at' => '1477017571',
            'status' => '1',
        ]);

        $this->insert('{{%menu}}', [
            'id' => '2',
            'title' => 'Нижнее меню',
            'slug' => 'niznee-menu',
            'icons' => '0',
            'max_levels' => '1',
            'author_id' => '1',
            'updater_id' => '1',
            'created_at' => '1477002611',
            'updated_at' => '1477017755',
            'status' => '1',
        ]);


        # {{%menu_items}}
        $this->insert('{{%menu_items}}', [
            'id' => '1',
            'menu_id' => '1',
            'parent_id' => 'NULL',
            'link_type' => '1',
            'title' => 'Home',
            'icon' => 'NULL',
            'url' => 'site/index',
            'show_child' => '1',
            'visible' => '1',
            'active' => 'site',
            'target' => 'NULL',
            'order' => '0',
            'level' => '1',
            'author_id' => 'NULL',
            'updater_id' => '1',
            'created_at' => 'NULL',
            'updated_at' => '1477015762',
            'status' => '1',
        ]);

        $this->insert('{{%menu_items}}', [
            'id' => '3',
            'menu_id' => '2',
            'parent_id' => 'NULL',
            'link_type' => '0',
            'title' => 'Bottom',
            'icon' => 'NULL',
            'url' => 'site/index',
            'show_child' => '0',
            'visible' => '0',
            'active' => 'NULL',
            'target' => 'NULL',
            'order' => 'NULL',
            'level' => 'NULL',
            'author_id' => '1',
            'updater_id' => '1',
            'created_at' => '1477002904',
            'updated_at' => '1477017301',
            'status' => '1',
        ]);

        $this->insert('{{%menu_items}}', [
            'id' => '4',
            'menu_id' => '1',
            'parent_id' => '1',
            'link_type' => '0',
            'title' => 'About',
            'icon' => 'NULL',
            'url' => 'site/about',
            'show_child' => '0',
            'visible' => '0',
            'active' => 'NULL',
            'target' => '1',
            'order' => '1',
            'level' => '2',
            'author_id' => '1',
            'updater_id' => '1',
            'created_at' => '1477007678',
            'updated_at' => '1477025674',
            'status' => '1',
        ]);


        # {{%tinypng_keys}}
        $this->insert('{{%tinypng_keys}}', [
            'id' => '1',
            'key' => '8M2p6b5SPJf0X33eFOM7Vm5ehnIumezq',
            'created_at' => '1470022424',
            'updated_at' => '1470030490',
            'valid_from' => '1469912400',
            'status' => '1',
        ]);

        $this->insert('{{%tinypng_keys}}', [
            'id' => '2',
            'key' => 'nIlG7iWv0aeuWlfVYt9IPw4PYycrv2sZ',
            'created_at' => '1470091662',
            'updated_at' => '1470091662',
            'valid_from' => '1470085200',
            'status' => '1',
        ]);

        $this->insert('{{%tinypng_keys}}', [
            'id' => '3',
            'key' => 'WDdEBcNyjaWZmvJYoXPvAcaHQwCjS0Js',
            'created_at' => '1470091756',
            'updated_at' => '1470091756',
            'valid_from' => '1470085200',
            'status' => '1',
        ]);


        # {{%user}}
        $this->insert('{{%user}}', [
            'id' => '1',
            'username' => 'webmaster',
            'auth_key' => 'wSleS9tEcNZndluiGLuOc-rDyNmSlZ9V',
            'access_token' => 'QllrLWm2WhGQbkW248pFJdAv42AEQ0RylNxms-S8',
            'password_hash' => '$2y$13$rhNmTGNHq8xXTnQArAPiRekJlDfyOrX/WDC7w8bIcqq.bY5kFHNiS',
            'oauth_client' => 'NULL',
            'oauth_client_user_id' => 'NULL',
            'email' => 'webmaster@example.com',
            'status' => '2',
            'created_at' => '1459770500',
            'updated_at' => '1476881378',
            'logged_at' => '1476996325',
        ]);

        $this->insert('{{%user}}', [
            'id' => '2',
            'username' => 'manager',
            'auth_key' => 'A_N90hlMWj59ilr2YA2iCp324i15hehv',
            'access_token' => 'rnW6L8x0ujc3bGN1nIdaHkI9eb7YGuYjnVaFV_Sv',
            'password_hash' => '$2y$13$MsFEGegdTLqeDR5ro1ZApeIshQZgwDhTsUBYbYzFKy6th6EMxqnOy',
            'oauth_client' => 'NULL',
            'oauth_client_user_id' => 'NULL',
            'email' => 'manager@example.com',
            'status' => '2',
            'created_at' => '1459770501',
            'updated_at' => '1476913288',
            'logged_at' => '1476911609',
        ]);

        $this->insert('{{%user}}', [
            'id' => '3',
            'username' => 'user',
            'auth_key' => '1QPWjcLKvWy8tcFgcsnxvvwLd4nHm_kM',
            'access_token' => 'vwCkcLF8rj_YqGIAhhZmhk7ln2lP7qEtIOqXrSU_',
            'password_hash' => '$2y$13$4o0QGWAUipqvEF/dlt20...JvOXW4whCMOV/.aayKju0jx70vbOZK',
            'oauth_client' => 'NULL',
            'oauth_client_user_id' => 'NULL',
            'email' => 'user@example.com',
            'status' => '2',
            'created_at' => '1459770502',
            'updated_at' => '1459770502',
            'logged_at' => 'NULL',
        ]);


        # {{%user_profile}}
        $this->insert('{{%user_profile}}', [
            'user_id' => '1',
            'firstname' => 'John',
            'middlename' => 'NULL',
            'lastname' => 'Doe',
            'avatar_path' => '1/J_KKATvdGWwRu9T2lOYW9Ci9dcUxQMoy.png',
            'avatar_base_url' => '/uploads',
            'locale' => 'ru-RU',
            'gender' => '1',
        ]);

        $this->insert('{{%user_profile}}', [
            'user_id' => '2',
            'firstname' => 'NULL',
            'middlename' => 'NULL',
            'lastname' => 'NULL',
            'avatar_path' => 'NULL',
            'avatar_base_url' => 'NULL',
            'locale' => 'ru-RU',
            'gender' => 'NULL',
        ]);

        $this->insert('{{%user_profile}}', [
            'user_id' => '3',
            'firstname' => 'NULL',
            'middlename' => 'NULL',
            'lastname' => 'NULL',
            'avatar_path' => 'NULL',
            'avatar_base_url' => 'NULL',
            'locale' => 'en-US',
            'gender' => 'NULL',
        ]);


        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');


        # {{%auth_assignment}}
        $this->delete('{{%auth_assignment}}', '[[item_name]] = :item_name AND [[user_id]] = :user_id', [
            'item_name' => 'administrator',
            'user_id' => '1',
        ]);
        $this->delete('{{%auth_assignment}}', '[[item_name]] = :item_name AND [[user_id]] = :user_id', [
            'item_name' => 'manager',
            'user_id' => '2',
        ]);
        $this->delete('{{%auth_assignment}}', '[[item_name]] = :item_name AND [[user_id]] = :user_id', [
            'item_name' => 'user',
            'user_id' => '3',
        ]);


        # {{%auth_item}}
        $this->delete('{{%auth_item}}', '[[name]] = :name', [
            'name' => 'administrator',
        ]);
        $this->delete('{{%auth_item}}', '[[name]] = :name', [
            'name' => 'editOwnModel',
        ]);
        $this->delete('{{%auth_item}}', '[[name]] = :name', [
            'name' => 'loginToBackend',
        ]);
        $this->delete('{{%auth_item}}', '[[name]] = :name', [
            'name' => 'manager',
        ]);
        $this->delete('{{%auth_item}}', '[[name]] = :name', [
            'name' => 'user',
        ]);


        # {{%auth_item_child}}
        $this->delete('{{%auth_item_child}}', '[[parent]] = :parent AND [[child]] = :child', [
            'parent' => 'user',
            'child' => 'editOwnModel',
        ]);
        $this->delete('{{%auth_item_child}}', '[[parent]] = :parent AND [[child]] = :child', [
            'parent' => 'manager',
            'child' => 'loginToBackend',
        ]);
        $this->delete('{{%auth_item_child}}', '[[parent]] = :parent AND [[child]] = :child', [
            'parent' => 'administrator',
            'child' => 'manager',
        ]);
        $this->delete('{{%auth_item_child}}', '[[parent]] = :parent AND [[child]] = :child', [
            'parent' => 'manager',
            'child' => 'user',
        ]);


        # {{%auth_rule}}
        $this->delete('{{%auth_rule}}', '[[name]] = :name', [
            'name' => 'ownModelRule',
        ]);


        # {{%file_storage_item}}
        $this->delete('{{%file_storage_item}}', '[[id]] = :id', [
            'id' => '2',
        ]);


        # {{%key_storage_item}}
        $this->delete('{{%key_storage_item}}', '[[key]] = :key', [
            'key' => 'app.company',
        ]);
        $this->delete('{{%key_storage_item}}', '[[key]] = :key', [
            'key' => 'app.name',
        ]);
        $this->delete('{{%key_storage_item}}', '[[key]] = :key', [
            'key' => 'backend.avatar.thumb',
        ]);
        $this->delete('{{%key_storage_item}}', '[[key]] = :key', [
            'key' => 'backend.list.thumb',
        ]);
        $this->delete('{{%key_storage_item}}', '[[key]] = :key', [
            'key' => 'frontend.maintenance',
        ]);
        $this->delete('{{%key_storage_item}}', '[[key]] = :key', [
            'key' => 'frontend.widget.cachetime',
        ]);


        # {{%menu}}
        $this->delete('{{%menu}}', '[[id]] = :id', [
            'id' => '1',
        ]);
        $this->delete('{{%menu}}', '[[id]] = :id', [
            'id' => '2',
        ]);


        # {{%menu_items}}
        $this->delete('{{%menu_items}}', '[[id]] = :id', [
            'id' => '1',
        ]);
        $this->delete('{{%menu_items}}', '[[id]] = :id', [
            'id' => '3',
        ]);
        $this->delete('{{%menu_items}}', '[[id]] = :id', [
            'id' => '4',
        ]);


        # {{%tinypng_keys}}
        $this->delete('{{%tinypng_keys}}', '[[id]] = :id', [
            'id' => '1',
        ]);
        $this->delete('{{%tinypng_keys}}', '[[id]] = :id', [
            'id' => '2',
        ]);
        $this->delete('{{%tinypng_keys}}', '[[id]] = :id', [
            'id' => '3',
        ]);


        # {{%user}}
        $this->delete('{{%user}}', '[[id]] = :id', [
            'id' => '1',
        ]);
        $this->delete('{{%user}}', '[[id]] = :id', [
            'id' => '2',
        ]);
        $this->delete('{{%user}}', '[[id]] = :id', [
            'id' => '3',
        ]);


        # {{%user_profile}}
        $this->delete('{{%user_profile}}', '[[user_id]] = :user_id', [
            'user_id' => '1',
        ]);
        $this->delete('{{%user_profile}}', '[[user_id]] = :user_id', [
            'user_id' => '2',
        ]);
        $this->delete('{{%user_profile}}', '[[user_id]] = :user_id', [
            'user_id' => '3',
        ]);


        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    private function checkTable($table) {
        if ($this->getDb()->getSchema()->getTableSchema($table, true) != null) {
            $this->dropTable($table);
        }
    }
}
