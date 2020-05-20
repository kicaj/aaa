<?php
namespace SlicesCake\Delete\Model\Behavior;

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
        $field = $this->getConfig('field');

        if ($this->getTable()->hasField($field)) {
            $deleted = true;

            if ($query->clause('where')) {
                $query->clause('where')->traverse(function ($expression) use (&$deleted, $field) {
                    if ($expression instanceof Comparison) {
                        if ($expression->getField() === $this->getTable()->getAlias() . '.' . $field) {
                            $deleted = false;
                        }
                    }
                });
            }

            if ($deleted === true) {
                $query->where([
                    $this->getTable()->getAlias() . '.' . $field . ' IS NULL',
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
        $field = $this->getConfig('field');

        if ($this->getTable()->hasField($field)) {
            foreach ($this->getTable()->associations() as $association) {
                $association->cascadeDelete($entity, [
                    '_primary' => false,
                ]);
            }

            $event->stopPropagation();

            if ($entity->isAccessible($field) === false) {
                $entity->setAccess($field, true);
            }

            $entity = $this->getTable()->patchEntity($entity, [
                $field => Time::now(),
            ]);

            return $this->getTable()->save($entity);
        }

        return true;
    }
}
