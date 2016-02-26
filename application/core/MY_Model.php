<?php defined("BASEPATH") or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
  protected $where = array();
  protected $where_in = array();
  protected $table;
  protected $select = '*';
  protected $order_by = NULL;
  protected $order_dir = NULL;
  protected $limit = NULL;
  protected $offset = NULL;
  protected $group_by = array();
  protected $joins = array();
  protected $where_not_in = array();

  public function __construct()
  {
    parent::__construct();
  }

  public function where($where, $value = NULL)
  {
      if(!is_array($where))
      {
          $where = array($where => $value);
      }

      array_push($this->where, $where);
      return $this;
  }

  public function where_in($col, $vals = NULL)
  {
      $this->where_in[$col] = $vals;
      return $this;
  }

  public function where_not_in($col, $vals)
  {
      $this->where_not_in[$col] = $vals;
      return $this;
  }
  /**
   * Set Select Section of DB Query
   * @param  mixed $select
   * @return self
   */
  public function select($select)
  {
      if(!is_array($select))
      {
          $this->select = $select;
      } else {
          $this->select = implode(',',$select);
      }
      return $this;
  }

  /**
   * Set order_by section of query
   * @param  string $col   Column to sort on
   * @param  string $order Direction to sort
   * @return self
   */
  public function order_by($col, $order = 'desc')
  {
      $this->order_by = $col;
      $this->order_dir = $order;
      return $this;
  }

  /**
   * Set limit seciton of query
   * @param  integer $limit
   * @return self
   */
  public function limit($limit)
  {
      $this->limit = $limit;
      return $this;
  }

  /**
   * Set offset section of query
   * @param integer $offset
   * @return self
   */
  public function offset($offset)
  {
      $this->offset = $offset;
      return $this;
  }

  /**
   * Set like section of DB query
   * @param  string $like     Column to run like against
   * @param  mixed $value
   * @param  string $position Type of liek statement
   * @return self
   */
  public function like($like, $value = NULL, $position = 'both')
  {
      if(!is_array($like))
      {
          $like = array($like => array(
              'value' => $value,
              'position' => $position,
          ));
      }

      array_push($this->like, $like);

      return $this;
  }

  public function join($table, $statement, $type)
  {
      $this->joins[] = array(
          'table' => $table,
          'statement' => $statement,
          'type' => $type
      );
      return $this;
  }

  /**
   * Execute the generated db query
   * @return self
   */
  public function fetch()
  {
      $this->db->select($this->select);
      $this->db->from($this->table);
      if(!empty($this->joins))
      {
          foreach($this->joins as $join)
          {
              $this->db->join($join['table'], $join['statement'], $join['type']);
          }
      }
      $this->joins = array();
      if(!empty($this->where))
      {
          foreach($this->where as $where)
          {
              $this->db->where($where);
          }
          $this->where = array();
      }

      if(!empty($this->where_in))
      {
          foreach($this->where_in as $key => $val)
          {
              $this->db->where_in($key, $val);
          }
          $this->where_in = array();
      }

      if(!empty($this->where_not_in))
      {
          foreach($this->where_not_in as $key => $val)
          {
              $this->db->where_not_in($key, $val);
          }
          $this->where_not_in = array();
      }

      if(!empty($this->like))
      {
          $this->db->like($this->like);
          $this->like = array();
      }

      if(!empty($this->group_by))
      {
          foreach($this->group_by as $col)
          {
              $this->db->group_by($col);
          }
          $this->group_by = array();
      }

      if(!is_null($this->limit) && !is_null($this->offset))
      {
          $this->db->limit($this->limit, $this->offset);
          $this->limit = NULL;
          $this->offset = NULL;
      }
      else if(!is_null($this->limit))
      {
          $this->db->limit($this->limit);
          $this->imit = NULL;
      }
      if(!is_null($this->order_by) && !is_null($this->order_dir))
      {
          $this->db->order_by($this->order_by, $this->order_dir);
          $this->order_by = NULL;
          $this->order_dir = NULL;
      }

      $this->response = $this->db->get();
      return $this;
  }

  public function group_by($col)
  {
      $this->group_by[] = $col;
      return $this;
  }
  /**
   * Hit DB with a manual query
   * @param  string $query
   * @param  array $args  Bound parameters for the Query
   * @return self
   */
  public function query($query, $args)
  {
      $this->response = $this->db->query($query, $args);
      return $this;
  }

  public function num_rows()
  {
      return $this->response->num_rows();
  }

  /**
   * Return first row of resultset
   * @return object
   */
  public function row()
  {
      return $this->response->row();
  }

  /**
   * Return entire resultset
   * @return array
   */
  public function result()
  {
      return $this->response->result();
  }

}
