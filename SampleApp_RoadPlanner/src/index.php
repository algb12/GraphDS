<?php
/**
 * This is the RoadPlanner sample app for GraphDS.
 * It may be primitive and incomplete, but it demonstrates nicely how GraphDS makes life easier when working with graphs.
 */
    // Force reporting of all errors/warnings/notices
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Composer's autoload
    require '../../vendor/autoload.php';

    // Declare GraphDS libraries
    use GraphDS\Graph\UndirectedGraph;
    use GraphDS\Algo\Dijkstra;
    use GraphDS\Algo\DijkstraMulti;
    use GraphDS\Algo\FloydWarshall;

    // Load JSON datafile and create array with data
    $datafile = file_get_contents('../data/roads.json');
    $data = json_decode($datafile, true);

    // Initial submit button text
    $submit_btn_text = 'Set country';

    // Clear query string on reset
    if (isset($_GET['reset'])) {
        header('Location:'.$_SERVER['PHP_SELF']);
    }

    // If country set
    if (isset($_GET['country'])) {
        $country = $_GET['country'];
        $submit_btn_text = 'Set cities';
    }

    // If country and cities set
    if (isset($_GET['country']) && isset($_GET['start']) && isset($_GET['destination'])) {
        $start_city = $_GET['start'];
        $dest_city = $_GET['destination'];

        // Initialize GraphDS undirected graph
        $g = new UndirectedGraph();

        // Populate graph with vertices from JSON
        foreach ($data['countries'][$country]['locations'] as $location) {
            $g->addVertex($location);
        }

        // Populate graph with edges from JSON
        foreach ($data['countries'][$country]['paths'] as $start => $s) {
            foreach ($s as $destination => $d) {
                $g->addEdge($start, $destination, $d['distance']);
            }
        }

        // Floyd-Warshall
        // --------------
        $fw = new FloydWarshall($g);
        $fw_starttime = round(microtime(true) * 1000000);
        $fw->run();
        $fw_res = $fw->get($start_city, $dest_city);
        $fw_benchmark = (round(microtime(true) * 1000000)) - $fw_starttime;

        // Create path info text
        $info_fw_path = 'To get from '.$start_city.' to '.$dest_city.':<br>';
        $stops = $fw_res['path'];
        $last = count($stops) - 1;
        foreach ($stops as $k => $stop) {
            if ($k !== $last) {
                $info_fw_path .= $stop.' → ';
            } else {
                $info_fw_path .= $stop;
            }
        }

        // Create distance info text
        $info_fw_dist = 'The distance is ';
        $info_fw_dist .= $fw_res['dist'];
        $info_fw_dist .= ' km.';

        // Dijkstra
        // --------
        $dijk = new Dijkstra($g);
        $dijk_starttime = round(microtime(true) * 1000000);
        $dijk->run($start_city);
        $dijk_res = $dijk->get($dest_city);
        $dijk_benchmark = (round(microtime(true) * 1000000)) - $dijk_starttime;

        // Create path info text
        $info_dijk_path = 'To get from '.$start_city.' to '.$dest_city.':<br>';
        $stops = $dijk_res['path'];
        $last = count($stops) - 1;
        foreach ($stops as $k => $stop) {
            if ($k !== $last) {
                $info_dijk_path .= $stop.' → ';
            } else {
                $info_dijk_path .= $stop;
            }
        }

        // Create distance info text
        $info_dijk_dist = 'The distance is ';
        $info_dijk_dist .= $dijk_res['dist'];
        $info_dijk_dist .= ' km.';

        // Multi-path Dijkstra
        // --------
        $dijk_mult = new DijkstraMulti($g);
        $dijk_mult_starttime = round(microtime(true) * 1000000);
        $dijk_mult->run($start_city);
        $dijk_mult_res = $dijk_mult->get($dest_city);
        $dijk_mult_benchmark = (round(microtime(true) * 1000000)) - $dijk_mult_starttime;

        // Create path info text
        $info_dijk_mult_path = 'To get from '.$start_city.' to '.$dest_city.':<br>';
        foreach ($dijk_mult_res['paths'] as $path) {
            $stops = $path;
            $last = count($stops) - 1;
            foreach ($stops as $k => $stop) {
                if ($k !== $last) {
                    $info_dijk_mult_path .= $stop.' → ';
                } else {
                    $info_dijk_mult_path .= $stop;
                }
            }
            $info_dijk_mult_path .= '<br>';
        }

        // Create distance info text
        $info_dijk_mult_dist = 'The distance is ';
        $info_dijk_mult_dist .= $dijk_mult_res['dist'];
        $info_dijk_mult_dist .= ' km.';
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GraphDS sample app - RoadPlanner</title>
</head>
<body>
    <h1>RoadPlanner</h1>
    <p>This sample app demonstrates shortest path finding betweem two destinations using GraphDS.</p>
    <p>It uses 2 algorithms for shortest path detection: Dijkstra's and Floyd-Warshall algorithm.</p>
    <p>Check the <tt>data</tt> folder for the reference map of Germany and the JSON file, which is easily modifiable to include any country.</p>
    <p>All the graph-related logic is handled by GraphDS and the relevant algorithms, which act as extensions to the core GraphDS library.</p>
    <p>For more about GraphDS, please see <a href="https://github.com/algb12/GraphDS">https://github.com/algb12/GraphDS</a>.</p>
    <hr>
    <p>Please select the start and destination city below, and the calculated shortest path to get to the destination city will be shown.</p>
    <p>To try the multi-path Dijkstra shortest path algorithm, choose country X, with start city A and destination city J.</p>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
        <?php
            if (empty($_GET['country'])) {
                $country_selector = '';
                foreach ($data['countries'] as $country => $cdata) {
                    $country_selector .= '<option value="'.$country.'">'.$country.'</option>';
                }

                echo '<div id="countryselect">
                    <label for="country">Start city:</label>
                    <select name="country">'.$country_selector.'</select>
                </div>';
            } else {
                echo '<input type="hidden" name="country" value="'.$_GET['country'].'">';
            }

            if (isset($_GET['country'])) {
                echo '<h3>Country: '.$country.'</h3>';
                $city_selector = '';
                foreach ($data['countries'][$country]['locations'] as $location) {
                    $city_selector .= '<option value="'.$location.'">'.$location.'</option>';
                }

                echo '<div id="startselect">
                    <label for="start">Start city:</label>
                    <select name="start">'.$city_selector.'</select>
                </div>';

                echo '<div id="destinationselect">
                    <label for="destination">Destination city:</label>
                    <select name="destination">'.$city_selector.'</select>
                </div>';
            }
        ?>
        <input type="submit" value="<?=$submit_btn_text?>">
        <input type="submit" name="reset" value="Reset">
    </form>
    <?php
        if (isset($_GET['country']) && isset($_GET['start']) && isset($_GET['destination'])) {
            echo '<h4>Dijkstra calculation:</h4>
            <p>'.$info_dijk_path.'</p>
            <p>'.$info_dijk_dist.'</p>
            <p>Algorithm execution took '.$dijk_benchmark.'&mu;s.</p>
            <h4>Multi-path Dijkstra calculation:</h4>
            <p>'.$info_dijk_mult_path.'</p>
            <p>'.$info_dijk_mult_dist.'</p>
            <p>Algorithm execution took '.$dijk_mult_benchmark.'&mu;s.</p>
            <h4>Floyd-Warshall calculation:</h4>
            <p>'.$info_fw_path.'</p>
            <p>'.$info_fw_dist.'</p>
            <p>Algorithm execution took '.$fw_benchmark.'&mu;s.</p>';
        }
    ?>
</body>
</html>
