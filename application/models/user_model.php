<?php
class User_Model extends CI_Model {

  private $levels;

  function __construct()
  {
        // Call the Model constructor
    parent::__construct();

    $this->roles = array(
      'lender' => 1,
      'borrower' => 2
      );
  }

  public function get_role_id($role)
  {
    return $this->roles[$role];
  }

  public function hashpass($password)
  {
    $salt = bin2hex(openssl_random_pseudo_bytes(22));
    $hash = crypt($password, $salt); 
    return $hash;
  }

  public function validate_user($user)
  {
    $sql = "SELECT * FROM user WHERE email = ?";
    $get = $this->db->query($sql, array($user['email']));

    if($get->num_rows > 0) 
    {
      $row = $get->row_array();
      if(crypt($user['password'], $row['password']) != $row['password'])
        return false;

      return $get->row_array();
    }
    return false;
  }

  public function checkUserExists($email)
  {
    $sql = "SELECT * FROM user WHERE email = ?";
    $get = $this->db->query($sql, array($email));

    if($get->num_rows > 0)
      return true;
    else
      return false;
  }

  /**
   * register_user() - inserts a user record into the database
   *
   * Takes a single $user_data parameter
   *
   * returns success or failure
   */ 
  function register_user($user_data)
  {
    // Get the role ID of the user (1 = Lender, 2 = Borrower)
    $role_id = $this->get_role_id($user_data['role']);
    $sql = "INSERT INTO user (first_name, last_name, email, password, user_role_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())";

    $result = $this->db->query($sql, 
                                array(
                                  $user_data['regFirstName'], 
                                  $user_data['regLastName'], 
                                  $user_data['regEmail'], 
                                  $user_data['regPassword'],
                                  $role_id
                                )
                              ); 
    if($this->db->affected_rows() > 0) {
      $user_id = $this->db->insert_id();
      if ($user_data['role'] == 'lender') {
        $sql = "INSERT INTO lender (user_id, amount, created_at, updated_at)
                VALUES (?, ?, NOW(), NOW())";
        $this->db->query($sql, array($user_id, intval($user_data['regMoneyToLend']))); 
      }
      else {
        $sql = "INSERT INTO borrower (user_id, amount_needed, amount_for, description, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())";
        $this->db->query($sql, array($user_id, intval($user_data['regMoneyNeeed']), $user_data['regNeedMoneyFor'], $user_data['regDescription']));
      }

      if($this->db->affected_rows() > 0) {
        return array('user_id' => $user_id, 'role_id' => $role_id);
      }
    }

    return false;
  }

  /**
   * getBorrower() - retrieves a borrowers information from the database
   *
   * Takes a $borrower_id parameter
   *
   * returns multiple rows
   */
   public function getBorrower($borrower_id)
   {
    $sql = "SELECT CONCAT(user.first_name, ' ', user.last_name) as borrower, borrower.amount_needed, 
            SUM(user_borrowed.amount_borrowed) AS raised
            FROM borrower
            LEFT JOIN user_borrowed ON user_borrowed.borrower_id = borrower.id
            LEFT JOIN user on user.id = borrower.user_id
            WHERE user.id = ?";
    
    $query = $this->db->query($sql, array($borrower_id));
    if ($query->num_rows > 0) 
      return $query->row_array();

    return array();
  }

  /**
   * getAllBorrowers() - retrieves all borrowers information from the database
   *
   * No parameters
   *
   * returns multiple rows
   */
   public function getAllBorrowers()
   {
    $sql = "SELECT DISTINCT CONCAT(user.first_name, ' ', user.last_name) as borrower_name, borrower.user_id, borrower.amount_for, 
            borrower.description, borrower.amount_needed AS amount_needed, SUM(user_borrowed.amount_borrowed) AS amount_raised 
            FROM user
            LEFT JOIN borrower on borrower.user_id = user.id
            LEFT JOIN user_borrowed on user_borrowed.borrower_id = borrower.id 
            WHERE user.user_role_id = 2 
            GROUP BY user.id";
    
    $query = $this->db->query($sql);
    if ($query->num_rows > 0) 
      return $query->result_array();

    return array();
  }

  /**
   * getLenderBorrowers() - retrieves all borrowers lent to by specific lender
   *
   * Takes a $lender_id paramater 
   *
   * returns multiple rows
   */
  public function getLenderBorrowers($lender_id)
  {
    $sql = "SELECT CONCAT(user.first_name, ' ', user.last_name) as borrower_name, borrower.user_id, borrower.amount_for, 
            borrower.description, borrower.amount_needed, user_lent.amount_lent AS lent, 
            SUM(user_borrowed.amount_borrowed) AS amount_raised
            FROM borrower
            LEFT JOIN user_borrowed on user_borrowed.borrower_id = borrower.id
            LEFT JOIN user_lent on user_lent.borrower_id = borrower.id
            LEFT JOIN lender on lender.id = user_lent.lender_id
            LEFT JOIN user on user.id = borrower.user_id
            WHERE lender.user_id = ? GROUP BY borrower.user_id";
    
    $query = $this->db->query($sql, array($lender_id));
    if ($query->num_rows > 0) 
      return $query->result_array();

    return array();
  }

  /**
   * getLendersByID() - retrieves all lenders lending money to a borrower
   *
   * Takes a $borrower_id parameter
   *
   * returns multiple rows
   */
  public function getLendersByID($borrower_id)
  {
    $sql = "SELECT CONCAT(user.first_name, ' ', user.last_name) AS lender_name, user.email, amount_lent AS amount 
            FROM user_lent
            LEFT JOIN lender ON lender.id = user_lent.lender_id
            LEFT JOIN borrower ON borrower.id = user_lent.borrower_id
            LEFT JOIN user ON lender.user_id = user.id
            WHERE borrower.user_id = ?";

    $query = $this->db->query($sql, array($borrower_id));

    if($query->num_rows > 0) 
      return $query->result_array();

    return array();
  }

  /**
   * getLender() - retrieves a lender's information from the database
   *
   * Takes a $lender_id parameter
   *
   * returns multiple rows
   */
  public function getLender($lender_id)
   {
    $sql = "SELECT CONCAT(user.first_name, ' ', user.last_name) as lender, amount AS balance
            FROM lender
            LEFT JOIN user on user.id = lender.user_id
            WHERE user.id = ?";
    
    $query = $this->db->query($sql, array($lender_id));
    if ($query->num_rows > 0) 
      return $query->row_array();

    return array();
  }

  /**
   * getLenderBalance() - retrieves a lender's balance
   *
   * Takes a $lender_id parameter
   *
   * returns multiple rows
   */
  public function getLenderBalance($lender_id)
  {
    $sql = "SELECT amount from lender WHERE user_id = ?";
    $query = $this->db->query($sql, array($lender_id));
    $row = $query->row_array();
    return $row['amount'];
  }

  /**
   * updateLenderBalance() - updates a lender's balance
   *
   * Takes a $lender_id parameter
   *
   * returns multiple rows
   */
  public function updateLenderBalance($lender_id, $lent)
  {
    //$sql = "UPDATE lender SET amount = (amount - ?) WHERE lender.user_id = ?";
    $sql = "UPDATE lender SET amount = (amount - ?) WHERE user_id = ?";
   
    $this->db->query($sql, array($lent, $lender_id));
    if($this->db->affected_rows() > 0) {
      return true;
    }
    return false;
  }

  /**
   * lendMoney() - lend money to a borrower
   *
   * Takes a $lender_id parameter
   *
   * returns multiple rows
   */
  public function lendMoney($lender_user_id, $post)
  { 
    // Get lender ID from lender table
    $lender_id = $this->getLenderID($lender_user_id);
    $borrower_id = $this->getBorrowerID($post['borrower_id']);

    $sql = "INSERT INTO user_lent(lender_id, borrower_id, amount_lent, created_at, updated_at)
            VALUES(?, ?, ?, NOW(), NOW())";

    $this->db->query($sql, array($lender_id, $borrower_id, intval($post['amountToLend'])));
    if($this->db->affected_rows() > 0) {
      $sql = "INSERT INTO user_borrowed(borrower_id, lender_id, amount_borrowed, created_at, updated_at)
              VALUES(?, ?, ?, NOW(), NOW())";
      $this->db->query($sql, array($borrower_id, $lender_id, intval($post['amountToLend'])));
      if($this->db->affected_rows() > 0) {     
        if ($this->updateLenderBalance($lender_user_id, $post['amountToLend'])) {
          return true;
        }
      }
    }
    return false;
  }

  public function getLenderID($lender_user)
  {
    $sql = "SELECT id from lender WHERE user_id = ?";
    $query = $this->db->query($sql, array($lender_user));
    $row = $query->row_array();
    return $row['id'];
  }

  public function getBorrowerID($borrower_user)
  {
    $sql = "SELECT id from borrower WHERE user_id = ?";
    $query = $this->db->query($sql, array($borrower_user));
    $row = $query->row_array();
    return $row['id'];
  }
}

// end of file