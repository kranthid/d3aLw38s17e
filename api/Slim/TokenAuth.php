<?php
require 'Middleware.php';

class TokenAuth extends \Slim\Middleware {

    private $model;
    public function __construct($model) {
        $this->model = $model;
    }

    /**
     * Deny Access
     *
     */
    public function deny_access() {
        $res = $this->app->response();
        $res->status(401);
    }

    /**
     * Check against the DB if the token is valid
     * 
     * @param string $token
     * @return bool
     */
    public function authenticate($userID, $token) {
        return $this->model->validateToken($userID, $token);
    }

    /**
     * Call
     *
     */
    public function call() {

        
        /*if (strpos($this->app->request()->getPathInfo(), '/login/:idp') === false && strpos($this->app->request()->getPathInfo(), '/login/:idp') !== -1) {
            $this->next->call();
            return;
        }*/
        if (preg_match('/login|register/', $this->app->request()->getPathInfo())) {
            $this->next->call();
            return;
        }
        //Get the token sent from jquery
        $userID = $this->app->request->headers->get('userID');
        $tokenAuth = $this->app->request->headers->get('oauthToken');
        //echo $this->authenticate($userID, $tokenAuth);
        //return;
        //Check if our token is valid
        if ($this->authenticate($userID, $tokenAuth)) {
            //Get the user and make it available for the controller
           // $usrObj = new \Subscriber\Model\User();
          //  $usrObj->getByToken($tokenAuth);
          //  $this->app->auth_user = $usrObj;
            //Update token's expiration
          //  \Subscriber\Controller\User::keepTokenAlive($tokenAuth);
            //Continue with execution
            $this->next->call();
        } else {
            $this->deny_access();
        }
    }

}