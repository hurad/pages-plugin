<?php

namespace Pages\Model\Entity;

use Cake\Database\Exception;
use Cake\Utility\Time;
use Cake\ORM\Entity;
use Users\Model\Entity\User;
use Utils\Model\AccessorTrait;
use Utils\Model\BasePeer;
use Utils\Model\EnumTrait;

/**
 * Page entity represents a single result row from a table. It exposes the
 * methods for retrieving and storing properties associated in this row.
 *
 * @method Page setId($id) Set page's id.
 * @method Page setParentId($parentId) Set page's parent id.
 * @method Page setUserId($userId) Set page's user id.
 * @method Page setTitle($title) Set page's title.
 * @method Page setSlug($slug) Set page's slug.
 * @method Page setContent($content) Set page's content.
 * @method getId() Get page's id.
 * @method getParentId() Get page's parent id.
 * @method getUserId() Get page's user id.
 * @method User getUser() Get user object.
 * @method getTitle() Get page's title.
 * @method getSlug() Get page's slug.
 * @method getContent() Get page's content.
 * @method getCommentCount() Get page's comment count.
 * @method Time getCreatedAt() Get page's created date.
 * @method Time getUpdatedAt() Get page's updated date.
 *
 * @package Pages\Model\Entity
 */
class Page extends Entity
{
    use AccessorTrait;
    use EnumTrait;

    const ID = 'Pages.id';
    const PARENT_ID = 'Pages.parent_id';
    const USER_ID = 'Pages.user_id';
    const TITLE = 'Pages.title';
    const SLUG = 'Pages.slug';
    const CONTENT = 'Pages.content';
    const STATUS = 'Pages.status';
    const COMMENT_STATUS = 'Pages.comment_status';
    const COMMENT_COUNT = 'Pages.comment_count';
    const LFT = 'Pages.lft';
    const RGHT = 'Pages.rght';
    const CREATED_AT = 'Pages.created_at';
    const UPDATED_AT = 'Pages.updated_at';

    const STATUS_TRASH = 'trash';
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_PUBLISH = 'publish';

    const COMMENT_STATUS_DISABLE = 'disable';
    const COMMENT_STATUS_CLOSE = 'close';
    const COMMENT_STATUS_OPEN = 'open';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. JournalPeer::$fieldNames[JournalPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array(
        BasePeer::TYPE_COLNAME => [
            self::ID,
            self::PARENT_ID,
            self::USER_ID,
            self::TITLE,
            self::SLUG,
            self::CONTENT,
            self::STATUS,
            self::COMMENT_STATUS,
            self::COMMENT_COUNT,
            self::LFT,
            self::RGHT,
            self::CREATED_AT,
            self::UPDATED_AT
        ],
        BasePeer::TYPE_FIELDNAME => [
            'id',
            'parent_id',
            'user_id',
            'title',
            'slug',
            'content',
            'status',
            'comment_status',
            'comment_count',
            'lft',
            'rght',
            'created_at',
            'updated_at',
        ],
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. JournalPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        BasePeer::TYPE_COLNAME => [
            self::ID => 0,
            self::PARENT_ID => 1,
            self::USER_ID => 2,
            self::TITLE => 3,
            self::SLUG => 4,
            self::CONTENT => 5,
            self::STATUS => 6,
            self::COMMENT_STATUS => 7,
            self::COMMENT_COUNT => 8,
            self::LFT => 9,
            self::RGHT => 10,
            self::CREATED_AT => 11,
            self::UPDATED_AT => 12
        ],
        BasePeer::TYPE_FIELDNAME => [
            'id' => 0,
            'parent_id' => 1,
            'user_id' => 2,
            'title' => 3,
            'slug' => 4,
            'content' => 5,
            'status' => 6,
            'comment_status' => 7,
            'comment_count' => 8,
            'lft' => 9,
            'rght' => 10,
            'created_at' => 11,
            'updated_at' => 12,
        ],
    );


    /** The enumerated values for this table */
    protected static $enumValueSets = [
        self::STATUS => [
            self::STATUS_TRASH,
            self::STATUS_DRAFT,
            self::STATUS_PENDING,
            self::STATUS_PUBLISH
        ],
        self::COMMENT_STATUS => [
            self::COMMENT_STATUS_DISABLE,
            self::COMMENT_STATUS_CLOSE,
            self::COMMENT_STATUS_OPEN
        ]
    ];

    /**
     * Gets the SQL value for Status ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     *
     * @return int SQL value
     */
    public static function getStatusSqlValue($enumVal)
    {
        return self::getSqlValueForEnum(self::STATUS, $enumVal);
    }

    /**
     * Gets the SQL value for Comment Status ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     *
     * @return int SQL value
     */
    public static function getCommentStatusSqlValue($enumVal)
    {
        return self::getSqlValueForEnum(self::COMMENT_STATUS, $enumVal);
    }

    /**
     * Get page's status.
     *
     * @return null|string
     * @throws \Cake\Database\Exception
     */
    public function getStatus()
    {
        $statusCol = self::translateFieldName(self::STATUS, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME);

        if (null === $this->_properties[$statusCol]) {
            return null;
        }

        $valueSet = self::getValueSet(self::STATUS);

        if (!isset($valueSet[$this->_properties[$statusCol]])) {
            throw new Exception('Unknown stored enum key: ' . $this->_properties[$statusCol]);
        }

        return $valueSet[$this->_properties[$statusCol]];
    }

    /**
     * Get page's comment status.
     *
     * @return null|string
     * @throws \Cake\Database\Exception
     */
    public function getCommentStatus()
    {
        $commentStatusCol = self::translateFieldName(self::COMMENT_STATUS, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME);

        if (null === $this->_properties[$commentStatusCol]) {
            return null;
        }

        $valueSet = self::getValueSet(self::COMMENT_STATUS);

        if (!isset($valueSet[$this->_properties[$commentStatusCol]])) {
            throw new Exception('Unknown stored enum key: ' . $this->_properties[$commentStatusCol]);
        }

        return $valueSet[$this->_properties[$commentStatusCol]];
    }

    /**
     * Set the value of [status] column.
     *
     * @param int $value New value
     *
     * @throws \Cake\Database\Exception
     * @return Page The current object (for fluent API support)
     */
    public function setStatus($value)
    {
        if ($value !== null) {
            $valueSet = self::getValueSet(self::STATUS);
            if (!in_array($value, $valueSet)) {
                throw new Exception(sprintf('Value "%s" is not accepted in this enumerated column', $value));
            }
            $value = array_search($value, $valueSet);
        }

        $statusCol = self::translateFieldName(self::STATUS, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME);

        if ($this->get($statusCol) !== $value) {
            $this->set($statusCol, $value);
        }


        return $this;
    }

    /**
     * Set the value of [comment_status] column.
     *
     * @param  int $value new value
     *
     * @throws \Cake\Database\Exception
     * @return Page The current object (for fluent API support)
     */
    public function setCommentStatus($value)
    {
        if ($value !== null) {
            $valueSet = self::getValueSet(self::COMMENT_STATUS);
            if (!in_array($value, $valueSet)) {
                throw new Exception(sprintf('Value "%s" is not accepted in this enumerated column', $value));
            }
            $value = array_search($value, $valueSet);
        }

        $commentStatusCol = self::translateFieldName(self::COMMENT_STATUS, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME);

        if ($this->get($commentStatusCol) !== $value) {
            $this->set($commentStatusCol, $value);
        }

        return $this;
    }

    /**
     * Get page status options.
     *
     * @param null|array $status
     *
     * @return array|string
     */
    public static function getStatusOptions($status = null)
    {
        $statuses = [
            self::STATUS_TRASH => __d('pages', 'Trash'),
            self::STATUS_DRAFT => __d('pages', 'Draft'),
            self::STATUS_PENDING => __d('pages', 'Pending'),
            self::STATUS_PUBLISH => __d('pages', 'Publish')
        ];

        return self::getEnumOptions($status, $statuses);
    }

    /**
     * Get page comment status options.
     *
     * @param null|array $status
     *
     * @return array|string
     */
    public static function getCommentStatusOptions($status = null)
    {
        $statuses = [
            self::COMMENT_STATUS_OPEN => __d('pages', 'Open'),
            self::COMMENT_STATUS_CLOSE => __d('pages', 'Close'),
            self::COMMENT_STATUS_DISABLE => __d('pages', 'Disable')
        ];

        return self::getEnumOptions($status, $statuses);
    }
}
