<?php

namespace App\Models\QueryBuilder;

class BaseQueryBuilder
{
  public function __construct($model)
  {
    $this->queryBuilder = $model;
    $this->model        = $model;
    $this->attributes   = [];
  }

  public function get($limit = 1000, $page = 1)
  {
    if ($limit > 1000) $limit = 1000;

    return $this->queryBuilder->take($limit)->skip(($page-1) * $limit)->get()->toArrayCamel();
  }

  public function first()
  {
    $result = $this->queryBuilder->first();
    if(isset($result)) return $result->toArrayCamel();
    else return null;
  }

  public function firstOrFail()
  {
    return $this->queryBuilder->firstOrFail()->toArrayCamel();
  }

  public function lists($attribute)
  {
    return $this->queryBuilder->lists($attribute);
  }

  public function count()
  {
    return $this->queryBuilder->count();
  }

  public function exists()
  {
    return !!$this->queryBuilder->first();
  }

  public function idIn($ids)
  {
    if(empty($ids)) return $this;

    $this->queryBuilder = $this->queryBuilder->whereIn('id', $ids);

    return $this;
  }

  public function idEquals($id)
  {
    if(!isset($id)) return $this;

    $this->queryBuilder = $this->queryBuilder->where($this->model->getTable() . '.id', $id);

    return $this;
  }

  public function archivesPpatIdEquals($id)
  {
    if(!isset($id)) return $this;

    $this->queryBuilder = $this->queryBuilder->where($this->model->getTable() . '.id', $id);

    return $this;
  }

  public function archivesPhipIdEquals($id)
  {
    if(!isset($id)) return $this;

    $this->queryBuilder = $this->queryBuilder->where($this->model->getTable() . '.id', $id);

    return $this;
  }

  public function idNotEquals($id)
  {
    if(!isset($id)) return $this;

    $this->queryBuilder = $this->queryBuilder->where('id', '<>', $id);

    return $this;
  }

  public function createOrUpdate($attributes)
  {
    if($this->queryBuilder->exists())
    {
      $this->queryBuilder->update($this->rawSnakeCaseAttributes($attributes));
    }
    else
    {
      return $this->queryBuilder->getModel()->create($this->snakeCaseAttributes(array_merge($this->attributes, $attributes)));
    }
  }

  public function create($attributes)
  {
    return $this->queryBuilder->getModel()->create($this->snakeCaseAttributes(array_merge($this->attributes, $attributes)));
  }

  public function update($attributes, $listenEvent = false)
  {
    if (isset($attributes['createdAt']))
      unset($attributes['createdAt']);
    if (isset($attributes['updatedAt']))
      unset($attributes['updatedAt']);

    if ($listenEvent)
    {
      foreach ($this->queryBuilder->get() as $model)
      {
        $model->update($this->snakeCaseAttributes($attributes));
      }
    }

    else return $this->queryBuilder->update($this->snakeCaseAttributes($attributes));
  }

  public function delete()
  {
    return $this->queryBuilder->delete();
  }

  public function forceDelete()
  {
    return $this->queryBuilder->forceDelete();
  }

  public function latest()
  {
    $this->queryBuilder = $this->queryBuilder->orderBy('created_at', 'DESC');

    return $this;
  }

  protected function snakeCaseAttributes($array = array(), $arrayHolder = array())
  {
    $underscoreArray = !empty($arrayHolder) ? $arrayHolder : array();
    foreach ($array as $key => $val) {
      $newKey = snake_case($key);
      if (!is_array($val)) {
        $underscoreArray[$newKey] = $val;
      } else {
        $underscoreArray[$newKey] = $this->snakeCaseAttributes($val, $underscoreArray[$newKey]);
      }
    }
    return array_filter($underscoreArray, 'strlen');;
  }


}