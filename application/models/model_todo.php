<?php

class Model_Todo extends Model
{
	protected $valid;
	
	public function __construct()
	{
		parent::__construct();

		$this->valid = new Validete();
	}

	public function paginatorData($count,int $from, $sort, $order)
	{
		$data = [];
		$validInjection = $this->valid->validInject($sort, $order);
		extract($validInjection);
		$query = "SELECT * FROM `todos` ORDER BY $sort $order ";
		$limit = "LIMIT $count";
		
		if ( $from > 0)
		{
			$from = --$from*$count;
			$limit = "LIMIT $from, $count";
		}
		$query .= $limit;
		$result = $this->query($query, PDO::FETCH_ASSOC) ?? '';

		if($result)
		{
			foreach ($result as $row) {
				$data[] =[
					'id' 		=> $row['id'],
					'name' 		=> $row['name'],
					'text' 		=> $row['text'],
					'email'		=> $row['email'],
					'status'	=> $row['status'],
				];
			}
		}
		return $data;
	}
	
	public function insert($name, $email, $text)
	{
		
		$name 	= $this->valid->validFields($name); 
		$email 	= $this->valid->validFields($email); 
		$text 	= $this->valid->validFields($text); 
		$created_at = date('Y-m-d H:i:s');
		
		$query = "INSERT INTO `todos`(`name`, `email`, `text`, `created_at`) VALUES ('$name', '$email', '$text', '$created_at')";
		$result = $this->query($query) ?? '';

		return $result;
	}

	public function getById($id)
	{
		$id = $this->valid->validFields($id);

		$query = "SELECT * FROM `todos` WHERE id=$id";
		$result = $this->query($query) ?? '';

		$row = $result->fetch();
		return $row;
	}

	public function update(int $id, $name, $email, $description, int $status=1)
	{
		$updated_at = date('Y-m-d H:i:s');

		$id 	= $this->valid->validFields($id);
		$name 	= $this->valid->validFields($name); 
		$email 	= $this->valid->validFields($email); 
		$text 	= $this->valid->validFields($description); 

		$query = "UPDATE `todos` SET `name`='$name',`email`='$email',`text`='$text',`status`= if(`status` < 2, $status, `status`),`updated_at`='$updated_at' WHERE id=$id";

		$result = $this->query($query) ?? '';

		return $result;
	}

	public function getCount($table)
	{
		$query = "SELECT COUNT( `id` ) as 'count' FROM $table WHERE 1";
		$result = $this->query($query) ?? '';
		$row = $result->fetch();

		return $row[0]; 
	}

	public function paginatorLinks($limit, $currentPage, $sort, $order)
	{

		$validInjection = $this->valid->validInject($sort, $order);
		extract($validInjection);

		$total =  $this->getCount('todos');
		$total++;
		if ($total <= $limit) return false;
		$paginator = new Pagination($total, $currentPage, $limit, $index='page', $sort, $order);
		return $paginator->links();
	}


	
}

class Validete
{
	public function validFields($var)
	{
		// можно тут сколько угодно валидировать 
		return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
	}
	
	public function validInject($sort, $order)
	{
		//захардкодил валидацию сортировки в массивах от инъекций
		$validSorts = array('id', 'name', 'email', 'status');
		$validOrders = array('ASC', 'DESC');
		$arr = array();
		in_array($sort, $validSorts) ? $arr['sort'] = $sort : $arr['sort'] = 'id';
		in_array($order, $validOrders) ? $arr['order'] = $order : $arr['order'] = 'ASC';
		return $arr;
	}
}
