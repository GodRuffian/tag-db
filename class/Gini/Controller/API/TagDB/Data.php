<?php

namespace Gini\Controller\API\TagDB;

class Data extends \Gini\Controller\API\Base
{
    public function actionSearchTags($criteria)
    {
        $token = md5(J($criteria));
        $keyword = $criteria['keyword'];
        $_SESSION[$token] = $criteria;
        $db = \Gini\Database::db();
        $count = $db->value("SELECT count(*) FROM `tag` WHERE `name` LIKE ".$db->quote('%'.$keyword.'%'));
        $data = [
            'total' => $count,
            'token' => $token,
        ];
        return $data;

    }

    public function actionGetTags($token, $start = 0, $limit = 20)
    {
        $db = \Gini\Database::db();
        $criteria = $_SESSION[$token];
        $result = [];
        $keyword = $criteria['keyword'];
        $rows = $db->query("SELECT * FROM `tag` WHERE `name` LIKE ".$db->quote('%'.$keyword.'%')." LIMIT {$start}, {$limit}")->rows();
        foreach ($rows as $row) {
            $result[] = [
                'name' => $row->name,
                'data' => $row->data,
            ];
        }
        return $result;
    }
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
