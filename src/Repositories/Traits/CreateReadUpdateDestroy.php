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
        return $this->read($this->continueOrNewQuery()->insertGetId($attributes));
    }

    /**
     * @param $id
     * @return Entity|null
     */
    public function read($id)
    {
        return $this->continueOrNewQuery()->where('id', $id)->first();
    }

    /**
     * @param $id
     * @param $attributes
     * @return Entity|null
     */
    public function update($id, $attributes)
    {
        $this->continueOrNewQuery();

        $this->query->where('id', $id)->update($attributes);

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
            $this->update($existing['id'], $values);
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
        return $this->continueOrNewQuery()->where('id', $id)->delete() == 1;
    }
}