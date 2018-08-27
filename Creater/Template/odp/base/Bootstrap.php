<?php

/**
 * @name Bootstrap
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Ap调用,
 * 这些方法, 都接受一个参数:Ap_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 * @author zhengzhiqing@zuoyebang.com
 */
class Bootstrap extends Ap_Bootstrap_Abstract
{
    /**
     * @param Ap_Dispatcher $dispatcher
     */
    public function _initRoute(Ap_Dispatcher $dispatcher) {
        $pathInfo = $_SERVER['PATH_INFO'];
        if (preg_match('/^\/view\/*/i', $pathInfo)){
            $request = $dispatcher->getRequest();
            $request->setRequestUri('/');
        }
    }

    /**
     * @param Ap_Dispatcher $dispatcher
     */
    public function _initDefaultName(Ap_Dispatcher $dispatcher) {
        //设置路由默认信息
        $dispatcher->setDefaultModule('Main');
        $dispatcher->setDefaultController('View');
        $dispatcher->setDefaultAction('Index');
    }

    /**
     * @param Ap_Dispatcher $dispatcher
     */
    public function _initPlugin(Ap_Dispatcher $dispatcher) {
        //注册saf插件
        $objPlugin = new Saf_ApUserPlugin();
        $dispatcher->registerPlugin($objPlugin);
    }

    /**
     * @param Ap_Dispatcher $dispatcher
     */
    public function _initView(Ap_Dispatcher $dispatcher) {
        //在这里注册自己的view控制器，例如smarty,firekylin
        $dispatcher->disableView();//禁止ap自动渲染模板
    }
}
