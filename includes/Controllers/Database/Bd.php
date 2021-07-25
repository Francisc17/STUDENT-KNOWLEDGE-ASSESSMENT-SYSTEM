<?php
class Bd
{
    public static $db;
    public $conn;
    private $servername = "localhost";
    private $username = "root";
    private $password = "123";
    private $dbname = "ppai2";

    /**
     * chamar função OpenCon()
     * Bd constructor.
     */
    function __construct(){
        $this->OpenCon();
    }


    /**
     *Estabelecer conexão com a base de dados
     */
    function OpenCon()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname",
                $this->username, $this->password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * @param $conn
     */
    function CloseCon($conn)
    {
        $this->conn = null;
    }

    /**
     * Obter instancia, devolve uma instancia da classe se ja houver uma criada,
     * caso não exista nenhuma criada, é chamado o construtor da classe para criar uma.
     * Metodo responsável por a classe ser um "singleton"
     * @return Bd
     */
    public static function getInstance()
    {
        if (self::$db == null)
            self:: $db = new Bd();
        return self::$db;
    }

    /**
     * destruct da classe
     */
    public static function destruct()
    {
        if (self::$db != null)
            self::$db = null;
    }


    /**
     * Obter obter conexao
     * @return PDO
     */
    public function getConn(): PDO
    {
        return $this->conn;
    }

    /**
     * destruct da classe
     */
    public function __destruct()
    {
        $this->conn = null;
    }
}