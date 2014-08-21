<?php

namespace Pages\Controller;

use Cake\Error\NotFoundException;
use Cake\ORM\TableRegistry;
use Pages\Model\Entity\Page;

/**
 * Pages Controller
 *
 * @package Pages\Controller
 */
class PagesController extends AppController
{
    /**
     * View single page
     *
     * @param null|int $id Page id
     *
     * @throws NotFoundException
     */
    public function view($id = null)
    {
        $pagesTable = TableRegistry::get('Pages.Pages');

        if (!$id && !$pagesTable->exists([Page::ID => $id])) {
            throw new NotFoundException(__('Invalid page'));
        }

        $this->set('page', $pagesTable->get($id));
    }
}
