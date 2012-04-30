<?

/*
 * class ApiController
 * Example for implementing a simple api class,
 * uses the Quantum\ApiOutput and Quantum\ApiException
 * classes for rendering data to the client.
 */

class ApiController extends Quantum\Controller {
    
    /*
     * __construct()
     * @param $arg
     */
    
    function __construct() {
        parent::__construct();
    }
    
    
    function index() {
        
        
        
    }
    
    /**
     * Example of user api endpoint:
     * You should access it as /api/user?id=
    */
    function user() {
        
        if (!isset($_REQUEST['id'])) {
            Quantum\ApiException::invalidParameters();
        }
       
       $user = User::find_by_id($_REQUEST['id']);
       
        if (empty($user)) {
            Quantum\ApiException::resourceNotFound();
        }
        
        $data['name'] = $user->name;
        $data['age'] = $user->age;
        $data['nickname'] = $user->nickname;
        $data['created_at'] = strtotime($user->created_at);
        $data['updated_at'] = strtotime($user->updated_at);
        
        Quantum\ApiOutput::adaptableOutput($data);
       
       
    }
    
   
    
}
