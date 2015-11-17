<?php

$authenticate = function ( $app ) {
    return function () use ( $app ) {
        $app->hybridInstance;
        $session_identifier = Hybrid_Auth::storage()->get('user');

        if (is_null( $session_identifier )) {
            echo 'Not logged in';
            //$app->redirect( '/login/' );
            return false;
        }else{
            return true;
        }
    };
};


$app->get( '/authenticate', $authenticate($app) );

/**
*
*Social login authentication
* :idp is Social Network Provider. Eg.Facebook/Twitter/Google
*
**/
$app->get( '/login/:idp', function ( $idp ) use ( $app, $model ) {
        try {
        	global $site_url;
            $adapter      = $app->hybridInstance->authenticate( ucwords( $idp ) );
            $user_profile = $adapter->getUserProfile();
            
            if (empty( $user_profile )) {
                $app->redirect( $site_url.'/login/?err=1' );
            }

            $identifier = $user_profile->identifier;

            if ($model->identifier_exists( $identifier )) {
                $model->login_user( $identifier );
                //echo "exist user". $redirect_url;
               	$app->redirect( $site_url."/home" );
            } else {
            	//provider, identifier, email, password, first_name, last_name, avatar_url

                $register = $model->register_user(
                    ucwords( $idp ),
                    $identifier,
                    $user_profile->email,
                    "",
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

            if ($model->localLoginAuth( $object -> user_id, $object -> password )) {
                $app->hybridInstance->storage()->set('user', $object -> user_id );
                echo 'success';
            }else{
                echo 'Wrong Credentials!';
                return 'Wrong Credentials!';
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
            if ($model->identifier_exists( $object -> user_id )) {
                echo 'UserID already exists!';
            } else {
                //provider, identifier, email, password, first_name, last_name, avatar_url
                $register = $model->register_user(
                    'Local',
                    $object -> user_id,
                    $object->email,
                    $object->passwd,
                    $object->firstname,
                    $object->lastname,
                    ''
                );

                if ($register) {
                    //$model->login_user( $object -> user_id );
                    $app->hybridInstance->storage()->set('user', $object -> user_id );
                    echo 'sucess';
                    //$app->redirect( $site_url.'/home' );
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


?>