<?

/*
 * class RegistryController
 * Simple Example of a registry class in quantum, includes a hook for post operations
 */

class RegistryController extends Quantum\Controller {
    
    /*
     * __construct()
     * @param $arg
     */
    
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        self::createUserHook();
    }
    
    function guid_test() {
        
        echo Quantum\Utilities::guid();
        
    }
    
    function thanks() {
        
    }
    
    function createUserHook() {
        
        if (isset($_POST['barcode'])) {
            
            $user = new User();
            $user->name = $_POST['name'];
            $user->nickname = $_POST['nickname'];
            $user->email = $_POST['email'];
            $user->zip_code = $_POST['zip_code'];
            $user->age = $_POST['age'];
            $user->save();
            
            //Quantum\Output::setMainView('registry', 'thanks');
            header ('Location: /registry/thanks', true, 302);
        }
        
    }
    
}
