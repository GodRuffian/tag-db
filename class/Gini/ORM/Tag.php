<?php

namespace Gini\ORM;

class Tag  extends Object
{
    public $name = 'string:30'; // 标签， 比如labmai-njust/123456
    public $data = 'array';

    protected static $db_index = [
        'unique:name',
    ];

}
