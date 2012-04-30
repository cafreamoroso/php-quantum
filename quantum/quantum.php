<?


class Quantum  {
   
    /**
     * Initialize here your database data
    */
    const QUANTUM_ROOT = '/path/to/quantum/';
    const DATABASE_PASS = 'YOUR_DB_PASS';
    const DATABASE_USER = 'YOUR_DB_USER';
    const DATABASE_HOST = 'localhost';
    const DATABASE_NAME = 'YOUR_DB_NAME';
    /**
     * End of quantum settings.
    */
    
    private $controller;
    private $task;
    private $object_id;
    
    
    /**
     * Meta class for booting Quantum, you can remove the output class and implement it manually
    */
    function __construct() {
        
        self::setQuantumVars();
        
        self::setAutoLoader();
        
        self::initActiveRecord();
        
        self::initSmarty();
        
        self::launcher();
        
        self::output();
        
    }
    
   
    
    /**
     * We get the data of the .htaccess with this function and assign it to quantum
    */
    private function setQuantumVars() {
        
        if (!empty($_REQUEST['controller'])) {
            
            $this->controller = $_REQUEST['controller'];
            
        };
        
         if (!empty($_REQUEST['task'])) {
            
            $this->task = $_REQUEST['task'];
            
        }
        
         if (!empty($_REQUEST['object_id'])) {
            
            $this->object_id = $_REQUEST['object_id'];
            
        }
        
        
    }
    
    /**
     * We set up the autoloader
    */
    private function setAutoLoader() {
        
        spl_autoload_register(array('self', 'autoLoader'));
        
    }
    
    
    /**
     * Thee autoloader...,  you can add more fileNameFormats, for ex: %s.class.php
    */
    private function autoLoader($className) {
        
         $directories = array(
            
              self::QUANTUM_ROOT.'system/controllers/',
              self::QUANTUM_ROOT.'system/controllers/quantum/',
              self::QUANTUM_ROOT.'system/lib/activerecord/',
              self::QUANTUM_ROOT.'system/lib/smarty/',
              self::QUANTUM_ROOT.'system/lib/quantum/controllers/',
            );
        
         
            $fileNameFormats = array(
              '%s.php',
              
            );
        
            $path = str_ireplace('_', '/', $className);
            
            $className = str_replace("Quantum\\" , '', $className);
            
            if(@include $path.'.php'){
                return;
            }
            
            foreach($directories as $directory){
                foreach($fileNameFormats as $fileNameFormat){
                    $path = $directory.sprintf($fileNameFormat, $className);
                    
                    if(file_exists($path)){
                        include $path;
                        return;
                    }
                }
            }
        
        
    }
    
    /**
     * This inits activerecord, those require once looks ugly, but in practice make it faster.
    */
    private function initActiveRecord() {
        
       require_once (Quantum::QUANTUM_ROOT.'system/lib/activerecord/ActiveRecord.php');
       
       ActiveRecord\Config::initialize(function($cfg)
         {
             $cfg->set_model_directory(Quantum::QUANTUM_ROOT.'system/models');
             $cfg->set_connections(array('development' => 'mysql://'.Quantum::DATABASE_USER.':'.Quantum::DATABASE_PASS.'@'.Quantum::DATABASE_HOST.'/'.Quantum::DATABASE_NAME.''));
         });
        
        
    }
    
     /**
     * This inits smarty, those require once looks ugly, but in practice make it faster.
    */
    private function initSmarty() {
        
        require_once (Quantum::QUANTUM_ROOT.'system/lib/smarty/Smarty.class.php');
        
        $smarty = new Smarty();
        $smarty ->template_dir = Quantum::QUANTUM_ROOT."system/views";
        $smarty->compile_dir =   Quantum::QUANTUM_ROOT."system/tmp";
        $smarty->allow_php_tag = true;
        $smarty->plugins_dir[] = Quantum::QUANTUM_ROOT.'system/plugins';
        
        Quantum\Output::init($smarty);
    }
    
    /**
     * You can boot here your database if you want to use sql outside of ActiveRecord
    */
    private function initDatabase() {
        
        
        
    }
    
    
    /**
     * Sintactic sugar for Quantum\Output::smartyRender
    */
    private function output() {
        
        Quantum\Output::smartyRender();
        
    }
    
    
    /**
     * This is the launcher, it will check for the first parameter of the url
     * and will try to check if a controller exists, if it does, it will launch it, and
     * the associated function, these parameters come from .htaccess. you should use mod_rewrite
    */
    private function launcher() {
        
       $controller = $this->controller;
       $task = $this->task;
       
       if (!isset($controller)) {
            $controller = 'IndexController';
        }
            else {
                $controller = ucfirst($controller).'Controller';
            }
        
        if (!isset($task)) {
            $task = 'index';
        }
        
        $c = new $controller();
      
        call_user_func(array($c, $task));
        
        Quantum\Output::setMainView($this->controller, $task);
        

    }
    
    
   
    
}
