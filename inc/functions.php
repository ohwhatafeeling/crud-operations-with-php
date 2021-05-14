<?php
//application functions
function get_project_list() {
  include('connection.php');

  try {
    return $db->query('SELECT project_id, title, category FROM Projects');
  } catch (Exception $e) {
    echo "Error!: " . $e->getMessage() . "</br>";
    return array();
  }
}

function get_task_list() {
  include('connection.php');

  try {
    return $db->query(
      'SELECT tasks.*, projects.title AS project FROM tasks
      JOIN projects ON tasks.project_id = projects.project_id'
    );
  } catch (Exception $e) {
    echo "Error!: " . $e->getMessage() . "</br>";
    return array();
  }
}

function add_project($title, $category) {
  include('connection.php');

  try {
    $sql = 'INSERT INTO projects(title, category) VALUES(?, ?)';
    $results = $db->prepare($sql);
    $results->bindValue(1, $title, PDO::PARAM_STR);
    $results->bindValue(2, $category, PDO::PARAM_STR);
    $results->execute();
  } catch (Exception $e) {
    echo "Error!:" . $e->getMessage() . "</br>";
    return false;
  }

  return true;
}

function add_task($project_id, $title, $date, $time) {
  include('connection.php');

  try {
    $sql = 'INSERT INTO tasks(project_id, title, date, time) VALUES(?, ?, ?, ?)';
    $results = $db->prepare($sql);
    $results->bindValue(1, $project_id, PDO::PARAM_INT);
    $results->bindValue(2, $title, PDO::PARAM_STR);
    $results->bindValue(3, $date, PDO::PARAM_STR);
    $results->bindValue(4, $time, PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e) {
    echo "Error!:" . $e->getMessage() . "</br>";
    return false;
  }

  return true;
}
