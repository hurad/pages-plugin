<?php

namespace Pages\Model\Validation;


use Cake\Validation\Validator;

class PageValidator extends Validator
{
    /**
     * Page validator constructor
     */
    function __construct()
    {
        $this
            ->notEmpty('title', __d('pages', 'Please fill this field'))
            ->add(
                'slug',
                'slugValidate',
                [
                    'message' => __d('pages', 'Slug can only be letters, numbers, dash and underscore'),
                    'rule' => function ($check) {
                            $regex = '/^[a-z]-?([a-z0-9]+(_|-)?)*[a-z0-9]$/';

                            if (preg_match($regex, trim($check))) {
                                return true;
                            }

                            return false;
                        }
                ]
            )
            ->notEmpty('slug', __d('pages', 'Please fill this field'));
    }
}