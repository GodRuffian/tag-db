<?php

namespace Gini\Controller\API\TagDB;

class Data extends \Gini\Controller\API\Base
{
    public function actionGet($name)
    {
        $tag = a('tag', ['name'=> $name]);
        return (array)$tag->data;
    }

    public function actionSet($name, array $data=[])
    {
        $tag = a('tag', ['name'=> $name]);
        if (!$tag->id) {
            $tag->name = $name;
        }
        $tag->data = $data;
        return !!$tag->save();
    }
}
