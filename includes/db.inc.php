<?php
class DB{
  private $config;
  function connecttodb(){
    $this->config=parse_ini_file('dbconfig.ini');
    try{
      $con = new PDO("mysql:host={$this->config['server']};dbname={$this->config['database']}",$this->config['username'],$this->config['password']);
      return $con;
    }
    catch(PDOException $e){
      print "PDO:Error!: {$e->getMessage()} <br/>";
    }
  }
}

