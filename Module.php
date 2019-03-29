<?php
 
namespace aradiv\debug;
 
use Yii;
use yii\debug\Module as DebugModule;
 
class Module extends DebugModule
{
    private $_basePath;
    private $rbacPermission = "debug";
    private $allowedUserIds = [];
    public function __construct($id, $parent = null, array $config = [])
    {
        if(isset($config['rbacPermission'])){
            $this->rbacPermission = $config['rbac_permission'];
        }
        if(isset($config['allowedUserIds'])){
          $this->allowedUserIds = $config['allowedUserIds'];
        }
    }
    
    /**
    * Show Debug bar if the current user has the respective permission or is in the list of userids that are allowed to access the debug bar
    */
    
    protected function checkAccess()
    {
        $user = Yii::$app->getUser();
        if (
            $user->identity &&
            ($user->can($this->rbacPermission) || in_array($user->id, $this->allowedUserIds))
        ) {
            return true;
        }
        return parent::checkAccess();
    }
 
    /**
     * Returns the root directory of the module.
     * It defaults to the directory containing the module class file.
     * @return string the root directory of the module.
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass(new yii\debug\Module('debug'));
            $this->_basePath = dirname($class->getFileName());
        }
 
        return $this->_basePath;
    }
}
