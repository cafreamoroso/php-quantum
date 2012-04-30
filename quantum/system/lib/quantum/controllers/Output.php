<?

/*
 * class QuantumController
 */

namespace Quantum;

class Output {
    
    
    public $smarty;
    public $view;
    
    function __construct() {
   
    }
    
    public function init($smarty) {
        
        $this->smarty = $smarty;
        
    }
    
    public function smartyRender() {
        
       $this->smarty->display(\Quantum::QUANTUM_ROOT.'system/views/'.$this->view);
    }
    
    public function setMainView($controller, $task) {
        
        if (empty($controller)) {
            
            $controller = 'index';
        }
        
        $this->view = "$controller/$task.tpl";
     
    }
    
   
    
    
    
}