<?php
include 'db.php';
require 'Slim/Slim.php';
require 'Login/Login_Model.php';
\Slim\Slim::registerAutoloader();

/*
Hybrid Login
*/
$site_url = "http://localhost/dealwebsite";
$login_config = 'Login/config.php';
require_once( "Login/Hybrid/Auth.php" );


$app = new \Slim\Slim();

$app->get('/auth','hybrid_authenticate');



$app->get('/users','getUsers');
$app->get('/updates','getUserUpdates');
$app->post('/updates', 'insertUpdate');
$app->delete('/updates/delete/:update_id','deleteUpdate');
$app->get('/users/search/:query','getUserSearch');

//Crud for deals
$app->get('/api/deals/all','getAllDealsData');
$app->get('/api/deals/dealcategory/:dealcategory/all','getAllDealsDataBasedOnCategory');
$app->get('/api/deals/dealId/:dealId','getDealsDataBasedOnId');
$app->post('/api/deal/create','createNewDeal');
$app->put('/api/deals/dealId/:dealId','updateExistingDeal');
$app->delete('/api/deals/dealId/:dealId','deleteExistingDeal');

//CRUD for categories
$app->get('/api/deals/dealcategories/all','getAllDealCategories');
$app->post('/api/deals/category/create','createNewDealMainCategory');
$app->get('/api/deals/category/:id','getCategoriesDataBasedOnId');
$app->put('/api/deals/category/:id','updateExistingDealCategory');
$app->delete('/api/deals/category/:id','deleteExistingCategory');

//CRUD for users
$app->get('/api/users/all','getAllUsers');
$app->post('/api/users/create','createNewUser');
$app->get('/api/user/userid/:id','getUserDataBasedOnId');
$app->put('/api/users/userid/:id','updateExistingUser');
$app->delete('/api/users/userid/:id','deleteExistingUser');


$app->container->singleton( 'hybridInstance', function () {
	global $login_config;
    $instance = new Hybrid_Auth($login_config);

    return $instance;
} );


$model = new \Model\Login_Model( getDB() );


$authenticate = function ( $app ) {
    return function () use ( $app ) {
        $app->hybridInstance;
        $session_identifier = Hybrid_Auth::storage()->get('user');

        if (is_null( $session_identifier ) && $app->request()->getPathInfo() != '/login/') {
            echo 'not logged in';
            //$app->redirect( '/login/' );
        }
    };
};


$app->get( '/', $authenticate($app) );


$app->get( '/login/', $authenticate( $app ), function () use ( $app ) {
			echo 'not logged in';
			//return 'false';
        //$app->render( 'login.php' );
    }
);


$app->get( '/login/:idp', function ( $idp ) use ( $app, $model ) {
        try {
        	global $site_url;
            $adapter      = $app->hybridInstance->authenticate( ucwords( $idp ) );
            $user_profile = $adapter->getUserProfile();
            //print_r($user_profile);
            //return;

            if (empty( $user_profile )) {
                $app->redirect( $site_url.'/login/?err=1' );
            }

            $identifier = $user_profile->identifier;

            if ($model->identifier_exists( $identifier )) {
                $model->login_user( $identifier );
                //echo "exist user". $redirect_url;
               	$app->redirect( $site_url."/home" );
            } else {
            	//$identifier, $email, $password, $provider, $screen_name, $first_name, $last_name, $avatar_url
    
                $register = $model->register_user(
                    $identifier,
                    $user_profile->email,
                    "",
                    ucwords( $idp ),
                    $user_profile->displayName,
                    $user_profile->firstName,
                    $user_profile->lastName,
                    $user_profile->photoURL
                );

                if ($register) {
                    $model->login_user( $identifier );
                    //echo "registered";
                   	$app->redirect( $site_url.'/home' );
                }

            }

        } catch ( Exception $e ) {
            echo $e->getMessage();
        }
    }
);

//Local login/registration
$app->post( '/local/login', function ( ) use ( $app, $model ) {
        try {
            global $site_url;

            $request = \Slim\Slim::getInstance()->request();
            $object = json_decode($request->getBody());

            if ($model->localLoginAuth( $object -> uid, $object -> pwd )) {
                echo 'true';
                $app->hybridInstance->storage()->set('user', $object -> uid );
            }else{
                echo 'uid/pwd wrong.';
            }
        } catch ( Exception $e ) {
            echo $e->getMessage();
        }
    }
);

//Local login/registration
$app->post( '/local/register', function ( ) use ( $app, $model ) {
        try {
            global $site_url;

                $request = \Slim\Slim::getInstance()->request();
                $object = json_decode($request->getBody());
                //$identifier, $email, $password, $provider, $screen_name, $first_name, $last_name, $avatar_url
    
                $register = $model->register_user(
                    $object -> $identifier,
                    $object->email,
                    $object->pwd,
                    $object->provider,
                    $object->displayName,
                    $object->firstName,
                    $object->lastName,
                    $object->photoURL
                );

                if ($register) {
                    $model->login_user( $identifier );
                    //echo "registered";
                    $app->redirect( $site_url.'/home' );
                }

            }

        } catch ( Exception $e ) {
            echo $e->getMessage();
        }
    }
);



$app->get( '/logout/', function () use ( $app, $model ) {
        $app->hybridInstance;
        $model->logout_user();
        Hybrid_Auth::logoutAllProviders();
        global $site_url;
        $app->redirect( $site_url );
    }
);

$app->get( '/welcome/', $authenticate( $app ), function () use ( $app, $model ) {
        $app->render( 'welcome.php', [ 'model' => $model ] );
    }
);

$app->run();

function hybrid_authenticate(){
	try{
               $hybridauth = new Hybrid_Auth( $login_config );
 
               $twitter = $hybridauth->authenticate( "Twitter" );
 
               $user_profile = $twitter->getUserProfile();
 
               echo "Hi there! " . $user_profile->displayName;
 
               $twitter->setUserStatus( "Hello world!" );
 
               $user_contacts = $twitter->getUserContacts();
           }
           catch( Exception $e ){
               echo "Ooophs, we got an error: " . $e->getMessage();
           }
}



function getUsers() {
	$sql = "SELECT user_id,username,name,profile_pic FROM users ORDER BY user_id";
	try {
		$db = getDB();
		$stmt = $db->query($sql);  
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"users": ' . json_encode($users) . '}';
	} catch(PDOException $e) {
	    //error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getUserUpdates() {
	$sql = "SELECT A.user_id, A.username, A.name, A.profile_pic, B.update_id, B.user_update, B.created FROM users A, updates B WHERE A.user_id=B.user_id_fk  ORDER BY B.update_id DESC";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql); 
		$stmt->execute();		
		$updates = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"updates": ' . json_encode($updates) . '}';
		
	} catch(PDOException $e) {
	    //error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getUserUpdate($update_id) {
	$sql = "SELECT A.user_id, A.username, A.name, A.profile_pic, B.update_id, B.user_update, B.created FROM users A, updates B WHERE A.user_id=B.user_id_fk AND B.update_id=:update_id";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);
        $stmt->bindParam("update_id", $update_id);		
		$stmt->execute();		
		$updates = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"updates": ' . json_encode($updates) . '}';
		
	} catch(PDOException $e) {
	    //error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function insertUpdate() {
	$request = \Slim\Slim::getInstance()->request();
	$update = json_decode($request->getBody());
	$sql = "INSERT INTO updates (user_update, user_id_fk, created, ip) VALUES (:user_update, :user_id, :created, :ip)";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("user_update", $update->user_update);
		$stmt->bindParam("user_id", $update->user_id);
		$time=time();
		$stmt->bindParam("created", $time);
		$ip=$_SERVER['REMOTE_ADDR'];
		$stmt->bindParam("ip", $ip);
		$stmt->execute();
		$update->id = $db->lastInsertId();
		$db = null;
		$update_id= $update->id;
		getUserUpdate($update_id);
	} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteUpdate($update_id) {
   
	$sql = "DELETE FROM updates WHERE update_id=:update_id";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("update_id", $update_id);
		$stmt->execute();
		$db = null;
		echo true;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	
}

function getUserSearch($query) {
	$sql = "SELECT user_id,username,name,profile_pic FROM users WHERE UPPER(name) LIKE :query ORDER BY user_id";
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"users": ' . json_encode($users) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getAllUsers(){
    $sqlCommand = "SELECT * FROM user WHERE 1";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $connectDb = null;
        echo '{"user": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    
}
function createNewUser(){
    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());
    $sqlCommand = "INSERT INTO user (Id,user_login,provide_id) VALUES (:Id,:user_login,:provide_id)";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);  
        $stmt->bindParam("Id", $insert->Id);
        $stmt->bindParam("user_login", $insert->user_login);
        $stmt->bindParam("provide_id", $insert->provide_id);
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    
}

function getUserDataBasedOnId($id){
    $queryParam = (string)$id;
    $sqlCommand = "SELECT * FROM user WHERE Id = '$queryParam'";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $connectDb = null;
        echo '{"user": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
function updateExistingUser($id){
    $request = \Slim\Slim::getInstance()->request();
    $update = json_decode($request->getBody());
    $queryParam = (string)$id;
    $sqlCommand = "UPDATE user SET Id = :Id, user_login = :user_login, provide_id = :provide_id WHERE Id = '$queryParam'";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);  
        $stmt->bindParam("Id", $update->Id);
        $stmt->bindParam("user_login", $update->user_login);
        $stmt->bindParam("provide_id", $update->provide_id);       
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
function deleteExistingUser($id){
    $request = \Slim\Slim::getInstance()->request();
    $queryParam = (string)$id;
    $sqlCommand = "DELETE FROM user WHERE Id = '$queryParam'";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);     
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"Deletion status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    
}
function getAllDealsData() {
    $sqlCommand = "SELECT * FROM deal_data WHERE 1";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $connectDb = null;
        echo '{"deal": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function getDealsDataBasedOnId($dealId) {
    $queryParam = (string)$dealId;
    $sqlCommand = "SELECT * FROM deal_data WHERE Id = '$queryParam'";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $connectDb = null;
        echo '{"deal": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
function getCategoriesDataBasedOnId($catId) {
    $queryParam = (string)$catId;
    $sqlCommand = "SELECT * FROM deal_main_category WHERE Id = '$queryParam'";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $connectDb = null;
        echo '{"dealCategories": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
function getAllDealsDataBasedOnCategory($dealcategory) {
    $queryParam = (string)$dealcategory;
    $sqlCommand = "SELECT * FROM deal_data WHERE deal_category = '$queryParam'";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $connectDb = null;
        echo '{"deal": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function createNewDeal(){
    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());
    $sqlCommand = "INSERT INTO deal_data (Id, user_id, deal_url, title, deal_price, deal_availablity,city_postcode
        ,deal_category,deal_topic,discount,discount_code,detail  
        ,prize,period ,deal_image ,deal_image_url
        ,tags,deal_rule,link_to_rule,apply_to,report,deal_like   
        ,deal_dislike,start_date,end_date,status) VALUES (:Id, :user_id, :deal_url, :title, :deal_price, :deal_availablity,:city_postcode
        ,:deal_category,:deal_topic,:discount,:discount_code,:detail  
        ,:prize,:period ,:deal_image ,:deal_image_url
        ,:tags,:deal_rule,:link_to_rule,:apply_to,:report,:deal_like   
        ,:deal_dislike,:start_date,:end_date,:status)";
echo ">>>>>>>>>>>>>>".$request->getBody();
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);  
        $stmt->bindParam("Id", $insert->Id);
        $stmt->bindParam("user_id", $insert->user_id);
        $stmt->bindParam("deal_url", $insert->deal_url);
        $stmt->bindParam("title", $insert->title);
        $stmt->bindParam("deal_price", $insert->deal_price);
        $stmt->bindParam("deal_availablity", $insert->deal_availablity);
        $stmt->bindParam("city_postcode", $insert->city_postcode);
        $stmt->bindParam("deal_category", $insert->deal_category);
        $stmt->bindParam("deal_topic", $insert->deal_topic);
        $stmt->bindParam("discount", $insert->discount);
        $stmt->bindParam("discount_code", $insert->discount_code);
        $stmt->bindParam("detail", $insert->detail);
        $stmt->bindParam("prize", $insert->prize);
        $stmt->bindParam("period", $insert->period);
        $stmt->bindParam("deal_image", $insert->deal_image);
        $stmt->bindParam("deal_image_url", $insert->deal_image_url);         
        $stmt->bindParam("tags", $insert->tags);
        $stmt->bindParam("deal_rule", $insert->deal_rule);
        $stmt->bindParam("link_to_rule", $insert->link_to_rule);
        $stmt->bindParam("apply_to", $insert->apply_to);
        $stmt->bindParam("report", $insert->report);
        $stmt->bindParam("deal_like", $insert->deal_like);
        $stmt->bindParam("deal_dislike", $insert->deal_dislike);
        $stmt->bindParam("start_date", $insert->start_date);
        $stmt->bindParam("end_date", $insert->end_date);
        $stmt->bindParam("status", $insert->status);
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
function updateExistingDeal($dealId){
    $request = \Slim\Slim::getInstance()->request();
    $update = json_decode($request->getBody());
    $queryParam = (string)$dealId;
    $sqlCommand = "UPDATE deal_data SET Id = :Id, user_id = :user_id, deal_url = :deal_url, title = :title, deal_price = :deal_price, deal_availablity = :deal_availablity,city_postcode = :city_postcode
        ,deal_category = :deal_category,deal_topic = :deal_category,discount = :deal_category,discount_code = :discount_code,detail =:detail 
        ,prize = :prize,period =:period ,deal_image =:deal_image ,deal_image_url = :deal_image_url
        ,tags = :tags,deal_rule = :deal_rule,link_to_rule = :link_to_rule,apply_to = :apply_to,report = :report,deal_like =:deal_like  
        ,deal_dislike = :deal_dislike,start_date = :start_date,end_date = :end_date,status = :status WHERE Id = '$queryParam'";
    
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);   
        $stmt->bindParam("Id", $update->Id);
        $stmt->bindParam("user_id", $update->user_id);
        $stmt->bindParam("deal_url", $update->deal_url);
        $stmt->bindParam("title", $update->title);
        $stmt->bindParam("deal_price", $update->deal_price);
        $stmt->bindParam("deal_availablity", $update->deal_availablity);
        $stmt->bindParam("city_postcode", $update->city_postcode);
        $stmt->bindParam("deal_category", $update->deal_category);
        $stmt->bindParam("deal_topic", $update->deal_topic);
        $stmt->bindParam("discount", $update->discount);
        $stmt->bindParam("discount_code", $update->discount_code);
        $stmt->bindParam("detail", $update->detail);
        $stmt->bindParam("prize", $update->prize);
        $stmt->bindParam("period", $update->period);
        $stmt->bindParam("deal_image", $update->deal_image);
        $stmt->bindParam("deal_image_url", $update->deal_image_url);         
        $stmt->bindParam("tags", $update->tags);
        $stmt->bindParam("deal_rule", $update->deal_rule);
        $stmt->bindParam("link_to_rule", $update->link_to_rule);
        $stmt->bindParam("apply_to", $update->apply_to);
        $stmt->bindParam("report", $update->report);
        $stmt->bindParam("deal_like", $update->deal_like);
        $stmt->bindParam("deal_dislike", $update->deal_dislike);
        $stmt->bindParam("start_date", $update->start_date);
        $stmt->bindParam("end_date", $update->end_date);
        $stmt->bindParam("status", $update->status);     
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function deleteExistingDeal($dealId){
    $request = \Slim\Slim::getInstance()->request();
    $queryParam = (string)$dealId;
    $sqlCommand = "DELETE FROM deal_data WHERE Id = '$queryParam'";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);   
        //$stmt->bindParam("Id", $update->Id);   
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"Deletion status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
function getAllDealCategories(){
    $sqlCommand = "SELECT * FROM deal_main_category WHERE 1";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->query($sqlCommand);  
        $dealCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
        $connectDb = null;
        echo '{"dealCategories": ' . json_encode($dealCategories) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }  
}
function createNewDealMainCategory(){
    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());
    $sqlCommand = "INSERT INTO deal_main_category (Id,category_name,status) VALUES (:Id,:category_name,:status)";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);  
         $stmt->bindParam("Id", $insert->Id);
         $stmt->bindParam("category_name", $insert->category_name);
        $stmt->bindParam("status", $insert->status);
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
function updateExistingDealCategory($id){
    $request = \Slim\Slim::getInstance()->request();
    $update = json_decode($request->getBody());
    $queryParam = (string)$id;
    $sqlCommand = "UPDATE deal_main_category SET Id = :Id, category_name = :category_name, status = :status WHERE Id = '$queryParam'";
    echo ">>>>>>>>>>>>>>".$sqlCommand;
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);  
        $stmt->bindParam("Id", $update->Id);
        $stmt->bindParam("category_name", $update->category_name);
        $stmt->bindParam("status", $update->status);       
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
function deleteExistingCategory($id){
    $request = \Slim\Slim::getInstance()->request();
    $queryParam = (string)$id;
    $sqlCommand = "DELETE FROM deal_main_category WHERE Id = '$queryParam'";
    try {
        $connectDb = createDataBaseConnection();
        $stmt = $connectDb->prepare($sqlCommand);   
        //$stmt->bindParam("Id", $update->Id);   
        $status = $stmt->execute(); 
        $connectDb = null;
        echo '{"Deletion status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
?>