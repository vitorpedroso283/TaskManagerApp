<?php
#Aqui é onde centralizei todas as rotas, assim caso haja necessidade de alterar uma rota, basta mudar apenas aqui.
#Há métodos para cada tipo, desde as views, models, controllers, routers e configs.
class Routes
{
    private static $roots = array(
        "models" => "/app/models/",
        "views" => "/app/views/",
        "controllers" => "/app/controllers/",
        "routers" => "/app/routers/",
        #PHP INCLUDES
        "configs" => "/app/config/",
        "libraries" => "/vendor/"
    );
    private static $models = array();
    private static $views = array();
    private static $controllers = array();
    private static $routers = array();
    private static $configs = array();
    private static $libraries = array();

    public static function getModelsRoutes()
    {
        return self::$models = array(
            "login" => self::$roots["models"] . "LoginModel.php",
            "taskManager" => self::$roots['models'] . "TaskManagerModel.php",
            "accessControl" => self::$roots['models'] . "AccessControlModel.php"
        );
    }

    public static function getViewsRoutes()
    {
        return self::$views = array(
            "includes" =>  self::$roots["views"] . "includes/",
            "login" => self::$roots['views'] . "login/",
            "home" => self::$roots['views'] . "home/",
            "accessControl" => self::$roots['views'] . "access-control/"
        );
    }

    public static function getControllersRoutes()
    {
        return self::$controllers = array(
            "login" => self::$roots["controllers"] . "LoginController.php",
            "taskManager" => self::$roots["controllers"] . "TaskManagerController.php",
            "accessControl" => self::$roots["controllers"] . "AccessControlController.php",
        );
    }

    public static function getRouters()
    {
        return self::$routers = array(
            "login" => self::$roots['routers'] . "loginRouter.php",
            "home" => self::$roots['routers'] . "homeRouter.php",
            "accessControl" => self::$roots['routers'] . "accessControlRouter.php",
            "taskManager" => self::$roots['routers'] . "taskManagerRouter.php",
        );
    }

    public static function getLibrariesRoutes()
    {
        return self::$libraries = array(
            "jquery" => self::$roots['libraries'] . "jquery/jquery-3.7.0",
            "bootstrap" => self::$roots['libraries'] . "bootstrap/bootstrap-5.3.0-alpha3",
            "jquery-mask" => self::$roots['libraries'] . "jquery-mask-plugin"
        );
    }

    public static function getConfigsRoutes()
    {
        return self::$configs = array(
            "database" => self::$roots["configs"] . "database.php",
            "authenticator" => self::$roots["configs"] . "authenticator.php",
        );
    }
}
