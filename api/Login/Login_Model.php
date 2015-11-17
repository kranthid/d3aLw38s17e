<?php
namespace Model;

class Login_Model
{

    /** @var object Database connection */
    private $conn;

    /**
     * Instantiate the model class.
     *
     * @param object $db_connection DB connection
     */
    public function __construct(\PDO $db_connection)
    {
        $this->conn = $db_connection;
    }

    /**
     * Check if a HybridAuth identifier already exists in DB
     *
     * @param int $identifier
     *
     * @return bool
     */
    public function identifier_exists($identifier)
    {
        try {
            $sql    = 'SELECT identifier FROM user';
            $query  = $this->conn->query($sql);
            $result = $query->fetchAll(\PDO::FETCH_COLUMN, 0);
            return in_array($identifier, $result);
        } catch ( \PDOException $e ) {

            die( $e->getMessage() );
        }

    }

    
    /**
     * Save users record to the database.
     *
     * @param string $identifier user's unique identifier
     * @param string $email
     * @param string $first_name
     * @param string $last_name
     * @param string $avatar_url
     *
     * @return bool
     */
    public function register_user( $provider, $identifier, $email, $password, $first_name, $last_name, $avatar_url)
    {
        try {
            $sql = "INSERT INTO user (provider, identifier, email, password, first_name, last_name, avatar_url) VALUES (:provider, :identifier, :email, :password, :first_name, :last_name, :avatar_url)";

            $query = $this->conn->prepare($sql);
            $query->bindValue(':provider', $provider);
            $query->bindValue(':identifier', $identifier);
            $query->bindValue(':email', $email);
            $query->bindValue(':password', $password);
            $query->bindValue(':first_name', $first_name);
            $query->bindValue(':last_name', $last_name);
            $query->bindValue(':avatar_url', $avatar_url);

            return $query->execute();
        } catch (\PDOException $e) {
            return $e->getMessage();
        }

    }

    

    /**
     * Create user login session
     *
     * @param int $identifier
     */
    public function login_user($identifier)
    {
        \Hybrid_Auth::storage()->set('user', $identifier);
    }


    /** Destroy user login session */
    public function logout_user()
    {
        \Hybrid_Auth::storage()->set( 'user', null );
    }

    /**
     * Return user's first name.
     *
     * @param int $identifier
     *
     * @return string
     */
    public function getFirstName( $identifier )
    {
        if ( ! isset( $identifier )) {
            return;
        }
        $query = $this->conn->prepare( "SELECT first_name FROM user WHERE identifier = :identifier" );
        $query->bindParam( ':identifier', $identifier );
        $query->execute();
        $result = $query->fetch( \PDO::FETCH_NUM );

        return $result[0];
    }


    /**
     * Return user's last name.
     *
     * @param int $identifier
     *
     * @return string
     */
    public function getLastName( $identifier )
    {
        if ( ! isset( $identifier )) {
            return;
        }
        $query = $this->conn->prepare( "SELECT last_name FROM user WHERE identifier = :identifier" );
        $query->bindParam( ':identifier', $identifier );
        $query->execute();
        $result = $query->fetch( \PDO::FETCH_NUM );

        return $result[0];
    }

    /**
     * Return user's email address
     *
     * @param int $identifier
     *
     * @return string
     */
    public function getEmail( $identifier )
    {
        if ( ! isset( $identifier )) {
            return;
        }
        $query = $this->conn->prepare( "SELECT email FROM user WHERE identifier = :identifier" );
        $query->bindParam( ':identifier', $identifier );
        $query->execute();
        $result = $query->fetch( \PDO::FETCH_NUM );

        return $result[0];
    }


    /**
     * Return the URL of user's avatar
     *
     * @param int $identifier
     *
     * @return string
     */
    public function getAvatarUrl( $identifier )
    {
        if ( ! isset( $identifier )) {
            return;
        }
        $query = $this->conn->prepare( "SELECT avatar_url FROM user WHERE identifier = :identifier" );
        $query->bindParam( ':identifier', $identifier );
        $query->execute();
        $result = $query->fetch( \PDO::FETCH_NUM );

        return $result[0];
    }


    /**
     * Return user.
     *
     * @param uid $uid, password $pwd
     *
     * @return string
     */
    public function localLoginAuth( $uid, $pwd )
    {
        if ( ! (isset( $uid ) && isset( $pwd ))) {
            return;
        }
        $query = $this->conn->prepare( "SELECT first_name FROM user WHERE identifier = :uid and password = :pwd" );
        $query->bindParam( ':uid', $uid );
        $query->bindParam( ':pwd', $pwd );
        $query->execute();
        $result = $query->fetch( \PDO::FETCH_NUM );

        return $result[0];
    }

}