<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class UsersMigration_100
 */
class UsersMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     * @throws Exception
     */
    public function morph(): void
    {
        $this->morphTable('users', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 11,
                        'first' => true
                    ]
                ),
                new Column(
                    'full_name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 128,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'email',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 128,
                        'after' => 'full_name'
                    ]
                ),
                new Column(
                    'password',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 128,
                        'after' => 'email'
                    ]
                ),
                new Column(
                    'created_at',
                    [
                        'type' => Column::TYPE_TIMESTAMP,
                        'default' => "current_timestamp()",
                        'notNull' => false,
                        'after' => 'password'
                    ]
                ),
                new Column(
                    'is_active',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'default' => "1",
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'created_at'
                    ]
                ),
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8mb4_uca1400_ai_ci',
            ],
        ]);
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up(): void
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $this->getConnection()->dropTable('users');
    }
}
