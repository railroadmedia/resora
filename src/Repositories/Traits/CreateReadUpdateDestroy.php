<?php

namespace Railroad\Resora\Repositories\Traits;

use Railroad\Resora\Decorators\Decorator;
use Railroad\Resora\Entities\Entity;
use Railroad\Resora\Events\Created;
use Railroad\Resora\Events\Creating;
use Railroad\Resora\Events\Destroyed;
use Railroad\Resora\Events\Destroying;
use Railroad\Resora\Events\Read;
use Railroad\Resora\Events\Updated;
use Railroad\Resora\Events\Updating;

trait CreateReadUpdateDestroy
{
    /**
     * @param $attributes
     * @return Entity|null
     */
    public function create($attributes)
    {
        event(new Creating(get_class($this), $attributes));

        $entity = $this->read($this->query()->insertGetId($attributes));

        if (!empty($entity)) {
            event(new Created(get_class($this), $attributes, $entity));
        }

        return $entity;
    }

    /**
     * @param $id
     * @return Entity|null
     */
    public function read($id)
    {
        $entity = $this->query()->where('id', $id)->first();

        if (!empty($entity)) {
            event(new Read(get_class($this), $id, $entity));
        }

        return $entity;
    }

    /**
     * If id is passed as number, we update a single row based on that id.
     * If the id param is an array, we pass it along to the query update method.
     *
     * @param $id|array
     * @param $attributes = null
     * @return int|Entity|null
     */
    public function update($id, $attributes = null)
    {
        $this->continueOrNewQuery();

        if (!empty($attributes) && !is_array($id)) {
            event(new Updating(get_class($this), $id, $attributes));

            $this->query->where('id', $id)->update($attributes);
        } else {
            return $this->query->update($id);
        }

        $entity = $this->read($id);

        event(new Updated(get_class($this), $id, $attributes, $entity));

        return $entity;
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Entity|null
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $existing = $this->query()->where($attributes)->first();

        if (!empty($existing)) {
            if (!empty($values)) {
                $this->update($existing['id'], $values);
            }
        } else {
            return $this->create(array_merge($attributes, $values));
        }

        return $this->read($existing['id']);
    }

    /**
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        event(new Destroying(get_class($this), $id));

        $bool = $this
            ->query()
            ->continueOrNewQuery()
            ->where('id', $id)->delete() == 1;

        if ($bool) {
            event(new Destroyed(get_class($this), $id));
        }

        return $bool;
    }
}
