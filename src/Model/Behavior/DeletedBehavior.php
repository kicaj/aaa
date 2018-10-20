<?php
namespace Delete\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Database\Expression\Comparison;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\Event\Event;
use Cake\I18n\Time;

class DeletedBehavior extends Behavior
{

    /**
     * {@inheritDoc}
     */
    protected $_defaultConfig = [
        'field' => 'deleted',
    ];

    /**
     * {@inheritDoc}
     */
    public function beforeFind(Event $event, Query $query)
    {
        if ($this->getTable()->hasField($this->_config['field'])) {
            $deleted = true;

            if ($query->clause('where')) {
                $query->clause('where')->traverse(function ($expression) use (&$deleted) {
                    if ($expression instanceof Comparison) {
                        if ($expression->getField() === $this->getTable()->getAlias() . '.' . $this->_config['field']) {
                            $deleted = false;
                        }
                    }
                });
            }

            if ($deleted === true) {
                $query->where([
                    $this->getTable()->getAlias() . '.' . $this->_config['field'] . ' IS NULL',
                ]);
            }
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeDelete(Event $event, EntityInterface $entity)
    {
        if ($this->getTable()->hasField($this->_config['field'])) {
            $event->stopPropagation();

            $entity = $this->getTable()->patchEntity($entity, [
                $this->_config['field'] => Time::now(),
            ]);

            return $this->getTable()->save($entity);
        }

        return true;
    }
}
