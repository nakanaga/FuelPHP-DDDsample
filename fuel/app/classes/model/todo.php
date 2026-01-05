<?php

/**
 * TodoのORMモデル
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property string $created_at
 * @property string|null $completed_at
 *
 * @package Model
 */
class Model_Todo extends \Orm\Model
{
    /** @var string テーブル名 */
    protected static $_table_name = 'todos';

    /** @var array カラム定義 */
    protected static $_properties = [
        'id',
        'title',
        'status',
        'created_at',
        'completed_at',
    ];

    /** @var array オブザーバー設定 */
    protected static $_observers = [
        'Orm\Observer_CreatedAt' => [
            'events' => ['before_insert'],
            'property' => 'created_at',
            'mysql_timestamp' => true,
        ],
        'Orm\Observer_UpdatedAt' => [
            'events' => ['before_update'],
            'property' => 'updated_at',
            'mysql_timestamp' => true,
        ],
    ];
}
