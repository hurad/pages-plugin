<?php

namespace Pages\Model\Table;

use Cake\ORM\Table;

/**
 * Class PagesTable
 *
 * @package Pages\Model\Table
 */
class PagesTable extends Table
{
    /**
     * Initialize a table instance. Called after the constructor.
     *
     * You can use this method to define associations, attach behaviors
     * define validation and do any other initialization logic you need.
     *
     * @param array $config Configuration options passed to the constructor
     *
     * @return void
     */
    public function initialize(array $config)
    {
        $this->belongsTo(
            'Users',
            [
                'className' => 'Users.Users',
                'foreignKey' => 'user_id',
                'joinType' => 'LEFT',
                'propertyName' => 'user'
            ]
        );

        $this->addBehavior('Tree');

        $this->addBehavior(
            'Timestamp',
            [
                'events' => [
                    'Model.beforeSave' => [
                        'created_at' => 'new',
                        'updated_at' => 'always',
                    ]
                ]
            ]
        );
    }
}
