<?php

class Controller_Todo extends Controller
{

  function action_index()
  { 
    unset($_SESSION['open_new_edit_window']);
    $currentPage = $_GET['page'] ?? 1;
    $currentSort = $_GET['sort'];
    $currentOrder = $_GET['order'];
    $model = new Model_Todo();
    $data = $model->paginatorData(PAGINATION_COUNT , $currentPage, $currentSort, $currentOrder);
    $paginator =  $model->paginatorLinks(PAGINATION_COUNT, $currentPage, $currentSort, $currentOrder);

    $this->view->generate('index_todo_view.php', 'template_view.php', $data, $paginator);
  }

  function action_create()
  { 
    unset($_SESSION['open_new_edit_window']);
    $this->view->generate('create_todo_view.php', 'template_view.php');
  }

  function action_store()
  { 
    unset($_SESSION['open_new_edit_window']);
  	$model = new Model_Todo();
    $result = $model->insert($_POST['name'], $_POST['email'], $_POST['description']);

    if($result)
      $_SESSION['message'] = "Успешно сохранено";
    else 
      $_SESSION['message'] = "Ошибка сохранения";

    header('Location:/todo/');
  }

  function action_edit()
  {     
    $this->relog();
    if(isset($_SESSION['open_new_edit_window'])){
      header('Location:/login/logout');
    } else {
      $_SESSION['open_new_edit_window'] = true;
    }

    $model = new Model_Todo();
    $data = $model->getById($_GET['id']);
    
    $this->view->generate('edit_todo_view.php', 'template_view.php', $data);
  }

  function action_update()
  { 
    $this->relog();
    unset($_SESSION['open_new_edit_window']);

    $model = new Model_Todo();
    if(!$_POST['status']) $_POST['status'] = 1;
    $result = $model->update($_GET['id'], $_POST['name'], $_POST['email'], $_POST['description'], $_POST['status']);

    if($result)
      $_SESSION["message"] = "Успешно сохранено";
    else 
      $_SESSION["message"] = "Ошибка сохранения";

    header('Location:/todo/');
  }

  function relog(){
    if($_SESSION['login_status'] !== 'access_granted'){
      $_SESSION["message"] = "Пройдите авторизацию";
      exit(header('Location:/login/'));
    }
  }
}
