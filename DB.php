<?php
class DB {
  private static $instance; //The single instance

  /**
   * Get an instance of the Database
   * @return Instance
   */
  public static function getInstance($host, $user, $pass, $db) {
    if (!self::$instance) {
      self::$instance = new self($host, $user, $pass, $db);
    }

    return self::$instance;
  }

  public function __construct($host, $user, $pass, $db) {
    $charset = 'utf8';
    $this->mysqli = new mysqli($host, $user, $pass, $db);
    $this->mysqli->set_charset($charset);
  }

  public function getConnectionError() {
    return $this->mysqli->connect_error;
  }

  /**
   * Bind the variable mysql
   * 
   * @param $stmt mysql statement object
   * @param $params Array of parameeters
   * @return $stmt mysql statement object 
   */
  private function bindDynamicVariables($stmt, $params) {
    if ($params != null) {
      $types = '';
      foreach($params as $param) {
        if(is_int($param)) {
          $types .= 'i';
        } elseif (is_float($param)) {
          $types .= 'd';
        } elseif (is_string($param)) {
          $types .= 's';
        } else {
          $types .= 'b';
        }
      }

      $bind_names[] = $types;

      for ($i=0; $i<count($params); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $params[$i];
        $bind_names[] = &$$bind_name;
      }
       
      call_user_func_array(array($stmt,'bind_param'), $bind_names);
    }
    return $stmt;
  }

  /**
   * Executes mysql query
   *
   * @param $sql  Sql connection object
   * @param $params [] params with key as column name and value
   * @param $types String types of object
   * @return $stmt executed statement
   *
   */
  public function executeQuery($sql, $params) {
    $stmt = $this->mysqli->prepare($sql);
    $stmt = $this->bindDynamicVariables($stmt, $params);
    $stmt->execute();
    return $stmt;
  }
}
