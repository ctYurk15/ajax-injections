<?php

class SQLCompiler
{
    protected $conn;
    
    protected $host;
    protected $db;
    protected $user;
    protected $pass;

    protected $blacklist = [];
    
    public function __construct($host, $user, $pass, $db, array $blacklist = [])
    {
        //initializing connection
        $this->conn = new mysqli($host, $user, $pass, $db);
        
        //storing connection data
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;

        //storing restricted sql sequences
        $this->blacklist = $blacklist;
    }
    
    public function getConnectionData()
    {
        return [$this->host, $this->user, $this->pass, $this->db];
    }

    public function getBlacklist()
    {
        return $this->blacklist;
    }
    
    public function testConnection()
    {
        $result = null;
        
        $result = (bool)$this->conn->query("SELECT true")->fetch_array()[0];
        
        return $result;
    }
    
    protected function sendSQL($query)
    {
        $result = $this->conn->query($query);
        return $result;
    }

    protected function blacklistCheck($query)
    {
        foreach($this->blacklist as $blcmd)
        {
            //if query contains banned sequences
            if(strpos(strtolower($query), strtolower($blcmd)) !== false)
            {
                return "Banned SQL - {$blcmd}";
            }
        }

        return true;
    }
    
    public function compile($query)
    {
        $result = null;
        
        $blacklistCheck = $this->blacklistCheck($query);

        //checking for banned sql
        if($blacklistCheck === true)
        {
            $response = $this->sendSQL($query);
        
            //checking if sql response is array or bool
            if(is_bool($response))
            {
                //if request was to change db data
                if($response)
                {
                    $result = $response;
                }
                //if server returned error
                else
                {
                    $result = "Error #".$this->conn->errno." - ".$this->conn->error;
                }
                
            }
            else
            {
                //if request was to get db data
                $result = [];
                while($row = $response->fetch_array())
                {
                    array_push($result, $row);
                }
            }

            return $result;
        }
        
        //some commands were banned
        return $blacklistCheck;
    }
}

?>