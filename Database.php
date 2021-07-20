<?php
/**
 * Database class.
 *
 * This class connect to database via PDO.
 *
 * @category   Database
 * @package    Core
 * @author     Ofer Elfassi and Dekel Ben-david
 */
class Database
{
    /* DB Params */
    private $host = '***********';
    private $db_name = '***********';
    private $username = '***********';
    private $password = '***********';

    public $conn;
    private $dsn;

    /**
     * Database constructor.
     * try to establish database connection
     */
    function __construct ()
    {
        $this->conn = null;

        try {
            $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            $error = "Database Error: ";
            $error .= $e->getMessage();
            require('src/views/db-error-page.php');
            exit();
        }
    }

    /**
     * Database destructor
     * close connection and clear memory when going out of scope
     */
    function __destruct(){
        if ($this->conn!==null) { $this->conn = null; }
    }

}