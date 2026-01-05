<?php

namespace Fuel\Migrations;

/**
 * Todosテーブル作成マイグレーション
 */
class Create_todos
{
    public function up()
    {
        \DBUtil::create_table('todos', [
            'id' => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'title' => ['type' => 'varchar', 'constraint' => 100],
            'status' => ['type' => 'varchar', 'constraint' => 20, 'default' => 'pending'],
            'created_at' => ['type' => 'datetime'],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'completed_at' => ['type' => 'datetime', 'null' => true],
        ], ['id'], false, 'InnoDB', 'utf8mb4_unicode_ci');
    }

    public function down()
    {
        \DBUtil::drop_table('todos');
    }
}
