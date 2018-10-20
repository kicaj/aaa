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
        if ($this->_table->hasField($this->_config['field'])) {
            $deleted = true;

            if ($query->clause('where')) {
                $query->clause('where')->traverse(function ($expression) use (&$deleted) {
                    if ($expression instanceof Comparison) {
                        if ($expression->getField() === $this->_table->getAlias() . '.' . $this->_config['field']) {
                            $deleted = false;
                        }
                    }
                });
            }

            if ($deleted === true) {
                $query->where($this->_table->getAlias() . '.' . $this->_config['field'] . ' IS NULL');
            }
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeDelete(Event $event, EntityInterface $entity)
    {
        if ($this->_table->hasField($this->_config['field'])) {
            $event->stopPropagation();

            $entity = $this->_table->patchEntity($entity, [
                $this->_config['field'] => Time::now()
            ]);

            return $this->_table->save($entity);
        }

        return true;
    }
}
