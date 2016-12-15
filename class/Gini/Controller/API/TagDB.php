<?php

namespace Gini\Controller\API;

class TagDB extends Base
{
    /**
        * @brief 重写构造函数，避免authorize断言判断
        *
        * @return
     */
    public function __construct()
    {
    }

    public function actionAuthorize($clientID, $clientSecret)
    {
        try {
            $cacheKey = "gapper#authorize#{$clientID}#{$clientSecret}";
            $data = self::cache($cacheKey);
            if (false===$data) {
                $conf = \Gini\Config::get('gapper.rpc');
                $rpc = \Gini\IoC::construct('\Gini\RPC', $conf['url']);
                $bool = $rpc->gapper->app->authorize($clientID, $clientSecret);
                $data = $bool ? 1 : 0;
                self::cache($cacheKey, $data);
            }
        }
        catch (\Exception $e) {
            throw new \Gini\API\Exception('网络故障', 503);
        }
        if ($data) {
            $this->setCurrentApp($clientID);
            return session_id();
        }
        throw new \Gini\API\Exception('非法的APP', 404);
    }

    public static function cache($key, $value=null)
    {
        $cacher = \Gini\Cache::of('defalut');
        if (is_null($value)) {
            return $cacher->get($key);
        }
        $config = \Gini\Config::get('cache.default');
        $timeout = @$config['timeout'];
        $timeout = is_numeric($timeout) ? $timeout : 500;
        $cacher->set($key, $value, $timeout);
    }
}
