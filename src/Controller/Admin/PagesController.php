<?php

namespace Pages\Controller\Admin;

use Cake\ORM\Query;
use Cake\Routing\Router;
use Cake\Utility\Time;
use DataTable\Column;
use DataTable\DataSource\ServerSide\CakePHP;
use DataTable\Table;
use Pages\Controller\AppController;
use Cake\ORM\TableRegistry;
use Pages\Model\Entity\Page;
use Pages\Model\Table\PagesTable;
use Pages\Model\Validation\PageValidator;

/**
 * Class PagesController
 *
 * @package Pages\Controller\Admin
 */
class PagesController extends AppController
{
    /**
     * Index page action
     */
    public function index()
    {
        $this->set('title_for_layout', __d('pages', 'Pages'));

        /** @var $pagesTable PagesTable */
        $pagesTable = TableRegistry::get('Pages.Pages');
        /** @var $query Query */
        $query = $pagesTable->find('all')
            ->contain('Users');

        if (!is_null($userId = $this->request->query('author'))) {
            $query->where([Page::USER_ID => $userId]);
        }

        $table = $this->getIndexDataTable($query);

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            echo $table->getResponse();
            return;
        }

        $this->set('table', $table->render());
    }

    /**
     * Get index DataTable
     *
     * @param Query $query CakePHP query
     *
     * @return Table
     */
    protected function getIndexDataTable(Query $query)
    {
        $table = new Table('pages');

        $titleCol = new Column();
        $titleCol->setTitle('Title')
            ->setData('Pages.title');

        $authorCol = new Column();
        $authorCol->setTitle('Author')
            ->setData('Users.username')
            ->setFormatter(
                function ($username, Page $page) {
                    return $this->createView()->Html->link($username, ['author' => $page->getUserId()]);
                }
            );

        $commentsCol = new Column();
        $commentsCol->setTitle('Comments')
            ->setData('Pages.comment_count')
            ->isSearchable(false);

        $dateCol = new Column();
        $dateCol->setTitle('Date')
            ->setData('Pages.created_at')
            ->isSearchable(false)
            ->setFormatter(
                function (Time $cell) {
                    return $cell->nice();
                }
            );

        $action = new Column\Action();
        $action->setManager(
            function (Column\ActionBuilder $action, Page $page) {
                $action->addAction(
                    'view',
                    __d('pages', 'View'),
                    Router::url(['prefix' => false, 'controller' => 'Pages', 'action' => 'view', $page->getId()]),
                    ['title' => __d('pages', 'View “%d”', $page->getTitle()), 'rel' => 'permalink']
                );

                $action->addAction(
                    'edit',
                    __d('pages', 'Edit'),
                    Router::url(['controller' => 'Pages', 'action' => 'edit', $page->getId()]),
                    ['title' => __d('pages', 'Edit this item')]
                );

                $action->addAction(
                    'delete',
                    __d('pages', 'Delete'),
                    Router::url(['action' => 'delete', $page->getId()]),
                    ['title' => __d('pages', 'Delete this item')]
                );
            }
        )
            ->setTitle('Action');

        $table->addColumn($titleCol)
            ->addColumn($authorCol)
            ->addColumn($commentsCol)
            ->addColumn($dateCol)
            ->addColumn($action);

        $table->setDataSource(new CakePHP($query, $this->request->here()));

        return $table;
    }

    /**
     * Add page action
     *
     * @return \Cake\Network\Response|void
     */
    public function add()
    {
        $this->set('title_for_layout', __d('pages', 'Add Page'));

        /** @var $pagesTable PagesTable */
        $pagesTable = TableRegistry::get('Pages.Pages');

        /** @var $page Page */
        $page = $pagesTable->newEntity($this->request->data);

        if ($this->request->is(['post', 'put'])) {
            if ($page->validate(new PageValidator())) {
                $page->setUserId($this->Auth->user('id'));
                $page->setTitle(trim($this->request->data('title')));
                $page->setSlug(trim($this->request->data('slug')));

                $pagesTable->save($page);

                $this->Flash->set(__d('pages', 'The page has been saved.'), ['element' => 'success']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->set(
                    __d('pages', 'The page could not be saved. Please, try again.'),
                    ['element' => 'danger']
                );
            }
        }

        $parentPages = $pagesTable->find('treeList');

        $this->set(compact('parentPages', 'page'));
    }

    /**
     * Edit page action
     *
     * @param int $id Page id
     *
     * @return \Cake\Network\Response|void
     */
    public function edit($id)
    {
        $this->set('title_for_layout', __d('pages', 'Edit Page'));

        /** @var $pagesTable PagesTable */
        $pagesTable = TableRegistry::get('Pages.Pages');

        /** @var $page Page */
        $page = $pagesTable->get($id);

        if ($this->request->is(['post', 'put'])) {
            $pagesTable->patchEntity($page, $this->request->data);

            if ($page->validate(new PageValidator())) {
                $page->setUserId($this->Auth->user('id'))
                    ->setTitle(trim($this->request->data('title')))
                    ->setSlug(trim($this->request->data('slug')))
                    ->setStatus($this->request->data('status'))
                    ->setCommentStatus($this->request->data('comment_status'));

                $pagesTable->save($page);

                $this->Flash->set(__d('pages', 'The page has been saved.'), ['element' => 'success']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->set(
                    __d('pages', 'The page could not be saved. Please, try again.'),
                    ['element' => 'danger']
                );
            }
        }

        $parentPages = $pagesTable->find('treeList')
            ->where([Page::ID . ' !=' => $page->getId()]);

        $this->set(compact('parentPages', 'page'));
    }

    /**
     * Delete page action
     *
     * @param int $id Page id
     *
     * @return \Cake\Network\Response|void
     */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        /** @var $pagesTable PagesTable */
        $pagesTable = TableRegistry::get('Pages.Pages');
        $page = $pagesTable->get($id);

        if ($pagesTable->delete($page)) {
            $this->Flash->set(__d('pages', 'Page deleted.'), ['element' => 'success']);
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->set(
                __d('pages', 'Page was not deleted. Please, try again.'),
                ['element' => 'danger']
            );
            return $this->redirect(['action' => 'index']);
        }
    }
} 