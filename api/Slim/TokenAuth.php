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
        
        if (preg_match('/login|register/', $this->app->request()->getPathInfo())) {
            $this->next->call();
            return;
        }
        //Get the token sent from jquery
        $userID = $this->app->request->headers->get('userID');
        $tokenAuth = $this->app->request->headers->get('oauthToken');
        
        //Check if our token is valid
        if ($this->authenticate($userID, $tokenAuth)) {
            //Continue with execution
            $this->next->call();
        } else {
            $this->deny_access();
        }
    }

}