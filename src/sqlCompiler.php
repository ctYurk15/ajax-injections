<?php

class SQLCompiler
{
    protected $conn;
    
    protected $host;
    protected $db;
    protected $user;
    protected $db_pass;
    protected $pass;

    protected $blacklist = [];
    
    public function __construct($host, $user, $db_pass, $db, array $blacklist = [], $pass = null)
    {
        //initializing connection
        $this->conn = new mysqli($host, $user, $db_pass, $db);
        
        //storing connection data
        $this->host = $host;
        $this->user = $user;
        $this->db_pass = $db_pass;
        $this->db = $db;

        //storing password
        $this->pass = $pass;

        //storing restricted sql sequences
        $this->blacklist = $blacklist;
    }
    
    public function getConnectionData()
    {
        return [$this->host, $this->user, $this->db_pass, $this->db];
    }

    public function getBlacklist()
    {
        return $this->blacklist;
    }

    public function getPass()
    {
        return $this->pass;
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
    
    public function compile($query, $pass = null)
    {
        $result = null;

        if($this->pass == null || $this->pass == $pass) //if we don`t need password to access SQL or password was correct
        {
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
                        return $response;
                    }
                    
                    //if server returned error
                    return "Error #".$this->conn->errno." - ".$this->conn->error;
                    
                }
                
                //if request was to get db data
                $result = [];

                //preparing array
                while($row = $response->fetch_array())
                {
                    array_push($result, $row);
                }

                return $result;
            
            }
            
            //show what was banned
            return $blacklistCheck;
        }
        
        if($pass != null)
        {
            return "Sorry, your password is not matching";
        }
        else
        {
            return "Your password is empty";
        }
        
    }
}

?>