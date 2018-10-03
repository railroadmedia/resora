<?php

namespace Railroad\Resora\Repositories\Traits;

use Railroad\Resora\Decorators\Decorator;
use Railroad\Resora\Entities\Entity;

trait CreateReadUpdateDestroy
{
    /**
     * @param $attributes
     * @return Entity|null
     */
    public function create($attributes)
    {
        return $this->read($this->query()->insertGetId($attributes));
    }

    /**
     * @param $id
     * @return Entity|null
     */
    public function read($id)
    {
        return $this->query()->where('id', $id)->first();
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
            $this->query->where('id', $id)->update($attributes);
        } else {
            return $this->query->update($id);
        }

        return $this->read($id);
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
        return $this
            ->query()
            ->continueOrNewQuery()
            ->where('id', $id)->delete() == 1;
    }
}
