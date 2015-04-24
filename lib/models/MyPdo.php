<?php

/*
 * Class: MyPdo
 *
 * Generate query to DB.
 */
class MyPdo
{
	static $_instance;
	protected $db;
	public $queryError;
	protected $select;
	protected $table;
	protected $where;
	protected $is;
	protected $whereArr;
	protected $order;
	protected $delete;
	protected $update;
	protected $insert;
	protected $old;
	protected $znak;
	protected $new;
	protected $query;
	protected $limit_start;
	protected $limit_end;
	protected $join;
	public $lastId;
	protected $set;


	/*
	 *Connect to DB.
	 */
	private function __construct()
	{
		try
		{
			$this->db = new PDO('mysql:host=' . DB_HOST . ';
				dbname=' . DB_NAME, DB_LOGIN, DB_PASS);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public static function getInstance()
	{
		if ( ! (self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __clone()
	{
	}

	/*
	*select
	*
	*@param val: takes value coll.
	*@return: this.
	*/
	public function select($val)
	{
		$this->select = '*';
		$val = $this->protect($val);
		if ('' == $val)
		{
			return $this;
		}
		else
		{
			$this->select = $val;
		}

		return $this;
	}

	/*
	*delete
	*
	*@return this
	*/
	public function delete()
	{
		$this->delete = 'DELETE FROM ';

		return $this;
	}

	/*
	*insert
	*@return this
	*/
	public function insert()
	{
		$this->insert = 'INSERT INTO ';

		return $this;
	}

	/*
	*update
	*@return this
	*/
	public function update()
	{
		$this->update = 'UPDATE ';

		return $this;
	}

	/*
	*table
	*
	*@param val: takes value table.
	*@return this
	*/
	public function table($val)
	{
		if ('' == trim($val))
		{
			$this->queryError .= 'Error. Table is empty!.';

			return $this;
		}
		else
		{
			$val = $this->protect($val);
			$this->table = $val;
		}

		return $this;
	}

	/*
	*where
	*
	*@param arr: takes array params.
	*@param znak: takes sign.
	*@return this
	*/
	public function where($arr, $znak)
	{
		if (!is_array($arr) && !is_array($znak))
		{
			$this->queryError .= 'Error. Wrong parametre WHERE';

			return $this;
		}
		else
		{
			$this->whereArr = $arr;
			$this->znak = $znak;
		}

		return $this;
	}

	 /*
	 *limit
	 *
	 *@param start: takes value coll.
	 *@param end: takes value coll.
	 *@return this
	 */
	public function limit($start, $end)
	{
		if ('' == trim($end) || '' == trim($start))
		{
			$this->queryError .= 'Error. Wrong parametre WHERE';

			return $this;
		}
		else
		{
			$start = $this->protect($start);
			$end = $this->protect($end);
			$this->limit_start = $start;
			$this->limit_end = $end;
		}

		return $this;
	}

	/*
	*set
	*
	*@param arr: takes array params.
	*@return this
	*/
	public function set($arr)
	{
		if (!is_array($arr))
		{
			$this->queryError .= 'Error. Wrong parametre SET';

			return $this;
		}
		else
		{
			$this->set = $arr;
		}

		return $this;
	}

	/*
	*order
	*
	*@param val: takes value coll order.
	*@return this
	*/
	public function order($val)
	{
		if ('' == trim($val))
		{
			$this->queryError .= 'Error. Wrong parametre ORDER';

			return $this;
		}
		else
		{
			$val = $this->protect($val);
			$this->order = $val;
		}

		return $this;
	}

	/*
	*Query builder
	*
	*@return this
	*/
	public function query()
	{
		$where = '';
		$order = '';
		$set = '';
		$limit = '';

		if (0 != count($this->set))
		{
			if (1 === count($this->set))
			{
				foreach ($this->set as $key => $val)
				{
					$set = 'SET '.$key.' = :'.$key.'';
				}
			}
			else
			{
				$set .= 'SET ';
				foreach ($this->set as $key => $val)
				{
					$set .= $key .' = :'.$key.', ';
				}
				$set = substr($set, 0, - 2);
			}
		}

		if (0 != count($this->whereArr))
		{
			if (1 === count($this->whereArr))
			{
				foreach ($this->whereArr as $key => $val)
				{
					$where = 'WHERE '.$key.' '.$this->znak[0].' :'.$key.'';
				}
			}
			else
			{
				$cnt = 0;
				$where .= 'WHERE ';
				foreach ($this->whereArr as $key => $val)
				{
					$where .= $key.' '.$this->znak[$cnt].' :'.$key.' AND ';
					$cnt ++;
				}
				$where = substr($where, 0, - 4);
			}
		}
		if (0 != strlen($this->order))
		{
			$order = 'ORDER BY '.$this->order;
		}
		if (0 != strlen($this->limit_end))
		{
			$limit = 'LIMIT :start, :end';
		}
		if (0 != strlen($this->select))
		{
			$this->select = 'SELECT '.$this->select.' FROM ';
		}
		$this->query = $this->select . $this->delete . $this->insert . $this->update . $this->table . ' ' . $set . ' ' . $where . ' ' . $order . ' ' . $limit;

		return $this;
	}

	/*
	*Checks input value
	*
	*@return: clean value.
	*/
	protected function protect($value)
	{
		$value = htmlspecialchars(trim($value));

		return $value;
	}

	/*
	*Prepare query and execute
	*
	*@return: success - array data, if false - error massage.
	*/
	public function commit()
	{
		if (0 != strlen($this->queryError))
		{
			return $this->queryError;
		}
		else
		{
			$stmt = $this->db->prepare($this->query);
		}

		if (!empty($this->set))
		{
			foreach ($this->set as $key => $val)
			{
				$stmt->bindValue(':'.$key, $val, PDO::PARAM_STR);
			}
		}

		if (!empty($this->whereArr))
		{
			foreach ($this->whereArr as $key => $val)
			{
				$stmt->bindValue(':'.$key, $val, PDO::PARAM_STR);
			}
		}
		if (0 != strlen($this->limit_start) && 0 != strlen($this->limit_end))
		{
			$stmt->bindValue(':start', (int)$this->limit_start, PDO::PARAM_INT);
			$stmt->bindValue(':end', (int)$this->limit_end, PDO::PARAM_INT);
		}
		$err = $stmt->execute();
		$this->lastId = $this->db->lastInsertId();
		if (false === $err)
		{
			$this->queryError = 'Wrong data!';

			return $this->queryError;
		}
		else
		{
			if ('' != trim($this->delete))
			{
				$arr = true;
				$this->defaultVar();

				return $arr;
			}
			if ('' != trim($this->update))
			{
				$arr = true;
				$this->defaultVar();

				return $arr;
			}
			if ('' != trim($this->insert))
			{
				$arr = true;
				$this->defaultVar();

				return $arr;
			}
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$arr = array();
			while ($row = $stmt->fetchAll())
			{
				$arr = $row;
			}
			$this->defaultVar();

			return $arr;
		}
	}

	/*
	 *Set empty all params
	 *
	 *@return: boolean.
	 */
	private function defaultVar()
	{
		$this->select = '';
		$this->table = '';
		unset($this->whereArr);
		$this->is = '';
		$this->order = '';
		$this->delete = '';
		$this->update = '';
		$this->insert = '';
		$this->old = '';
		$this->new = '';
		$this->query = '';
		$this->limit_start = '';
		$this->limit_end = '';
		unset($this->set);
		return true;
	}
}
?>