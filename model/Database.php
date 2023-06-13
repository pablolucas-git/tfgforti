<?php

require_once 'inc/config.php';
class Database
{
    protected $connection = null;
    public function __construct()
    {
        try {
            $servername = "localhost";
            $username = "forti";
            $password = "forti";
            $dbname = "forti";

        // Create connection
        $this->connection = new mysqli($servername, $username, $password, $dbname);
    	
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }			
    }
    public function select($query)
    {
        try {
            $result = $this->connection->query($query);
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function insert($query)
    {
        try {
            $result = $this->connection->query($query);
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($query)
    {
        try {
            $result = $this->connection->query($query);
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update($query)
    {
        try {
            $result = $this->connection->query($query);
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function executeStatement($query = "" , $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
            if( $params ) {
                $stmt->bind_param($params[0], $params[1]);
            }
            $stmt->execute();
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }	
    }
}