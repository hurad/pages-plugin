<?php

namespace Pages\Controller;

use App\Controller\AppController as BaseController;
use Users\Model\Entity\User;
use App\Lib\Hurad;

/**
 * Class AppController
 *
 * @package Pages\Controller
 */
class AppController extends BaseController
{
    /**
     * Called before the all controller action
     *
     * @param array $user Current user
     *
     * @return bool
     */
    public function isAuthorized($user)
    {
        switch ($user['role']) {
            case User::ROLE_ADMINISTRATOR:
                return Hurad::allowAuth();
                break;
            case User::ROLE_EDITOR:
            case User::ROLE_AUTHOR:
            case User::ROLE_USER:
                return Hurad::denyAuth();
                break;
            default:
                return Hurad::denyAuth();
                break;
        }
    }
}
