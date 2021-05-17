<?php
require 'inc/functions.php';

$page = "reports";
$pageTitle = "Reports | Time Tracker";
$filter = 'all';

if (!empty($_GET['filter'])) {
  $filter = explode(':', filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING));
}

include 'inc/header.php';
?>
<div class="col-container page-container">
    <div class="col col-70-md col-60-lg col-center">
        <div class="col-container">
            <h1 class='actions-header'>Reports on <?php
            if (!is_array($filter)) {
              echo "All Tasks by Project";
            } else {
              echo ucwords($filter[0]) . " : ";
              switch ($filter[0]) {
                case 'project':
                  $project = get_project($filter[1]);
                  echo $project['title'];
                  break;
                case 'category':
                  echo $filter[1];
                  break;
                case 'date':
                  echo $filter[1] . " - " . $filter[2];
                  break;
              }
            }
            ?></h1>
            <form class='form-container form-report' action='reports.php' method='get'>
              <label for='filter'>Filter:</label>
              <select id='filter' name='filter'>
                <option value=''>Select one:</option>
                <optgroup label="Project">
                <?php
                foreach (get_project_list() as $item) {
                  echo '<option value="project:' . $item['project_id'] . '">';
                  echo $item['title'] . "</option>\n";
                }
                ?>
                </optgroup>
                <optgroup label="Category">
                  <option value="category:Billable">Billable</option>
                  <option value="category:Charity">Charity</option>
                  <option value="category:Personal">Personal</option>
                </optgroup>
                <optgroup label="Date">
                  <option value="date:<?php
                  echo date('m/d/Y', strtotime('-2 Sunday'));
                  echo ":";
                  echo date('m/d/Y', strtotime('-1 Saturday'));
                  ?>">Last Week</option>;
                  <option value="date:<?php
                  echo date('m/d/Y', strtotime('-1 Sunday'));
                  echo ":";
                  echo date('m/d/Y');
                  ?>">This Week</option>;
                  <option value="date:<?php
                  echo date('m/d/Y', strtotime('first day of last month'));
                  echo ":";
                  echo date('m/d/Y', strtotime('last day of last month'));
                  ?>">Last Month</option>;
                  <option value="date:<?php
                  echo date('m/d/Y', strtotime('first day of this month'));
                  echo ":";
                  echo date('m/d/Y');
                  ?>">This Month</option>;
                </optgroup>
              </select>
              <input class="button" type="submit" value="run" />
            </form>
        </div>
        <div class="section page">
            <div class="wrapper">
                <table>
                    <?php
                    $totalTime = $project_id = $project_total = 0;
                    $tasks = get_task_list($filter);
                    foreach($tasks as $item) {
                      if ($project_id != $item['project_id']) {
                        $project_id = $item['project_id'];
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>" . $item['project'] . "</th>";
                        echo "<th>Date</th>";
                        echo "<th>Time</th>";
                        echo "</tr>";
                        echo "</thead>";
                      }
                      $project_total += $item['time'];
                      $totalTime += $item['time'];
                      echo "<tr>\n";
                      echo "<td>" . $item['title'] . "</td>";
                      echo "<td>" . $item['date'] . "</td>";
                      echo "<td>" . $item['time'] . "</td>";
                      echo "</tr>\n";
                      if (next($tasks)['project_id'] != $item['project_id']) {
                        echo "<tr>";
                        echo "<th class='project-total-label' colspan='2'>Project Total</th>\n";
                        echo "<th class='project-total-number'>$project_total</th>\n";
                        echo "</tr>\n";
                        $project_total = 0;
                      }
                    }
                    ?>
                    <tr>
                        <th class='grand-total-label' colspan='2'>Grand Total</th>
                        <th class='grand-total-number'><?php echo $totalTime; ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
