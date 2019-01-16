<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 14.01.19
 * Time: 0:11
 */

namespace Core\Model;


class AbstractModel
{



    public function mapArrayToModel(...$arr)
    {
        if (\count($arr) > 1) {
            [$model, $data] = $arr;
        } else {
            $model = $this;
            $data = $arr[1];
        }

        if (\is_string($model)) {
            $model = new $model();
        } else if ($model instanceof \Closure) {
            $model = $model();
        }
        foreach ($data as $key => $value) {
            $method = 'set' . $this->normalizeKey($key);

            if (method_exists($model, $method)) {
                $model->$method($value);
            }
        }

        return $model;
    }

    public function mapModelToArray($model = null)
    {
        if ($model === null) {
            $model = $this;
        }

        if (\is_array($model) || \is_object($model)) {
            $result = array();
            foreach ($model as $key => $value) {
                $result[$key] = $this->mapModelToArray($value ?? '');
            }
            return $result;
        }
        return $model;
    }

    /**
     * @param string $key
     * @return string
     */
    public function normalizeKey($key)
    {
        $search_replace = array("_", '-');
        $option = str_replace($search_replace, ' ', strtolower($key));
        return  str_replace(' ', '', ucwords($option));

    }
}