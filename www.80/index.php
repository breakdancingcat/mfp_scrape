<?php
ini_set('display_errors', 0);

// build a form
// get user name
// get date range
// foreach date in range
// scrape the dom and get the net carb
// net carb below 50 is good, above 50 is bad
// store each day in an array
// calculate how many days in a row carbs were below 50, this is a streak
// output how many streaks occured in date range

if ($_POST["submit"]=="submit" && $_POST["username"]!='' && $_POST["start_date"]!='' && $_POST["end_date"]!='') {

$startDate = strtotime($_POST["start_date"]);
$endDate = strtotime($_POST["end_date"]);
$currentDate = $startDate;
$netCarbs = array();

    while ($currentDate <= $endDate) {

    $html = file_get_contents('http://www.myfitnesspal.com/food/diary/' . $_POST["username"] . '?date=' . date('Y-m-d', $currentDate));

    // Create a new DOM element to do something with
    $dom = new DOMDocument();

    // Load the HTML
    $dom->loadHTML($html);

    $xpath = new DOMXPath($dom);

    $totals = $xpath->query('//tr[@class="total"]');

    $netCarb = array();
    foreach ($totals as $total) {
        //$new_total = explode(PHP_EOL, $total->textContent);
        $new_total = preg_split("/\\r\\n|\\r|\\n/", $total->textContent);
        foreach ($new_total as $key=>$value) {
            if (trim($value) != '') {
                $netCarb[$key] = trim($value);
            }
        }
    }
    $netCarbs[date('Y-m-d', $currentDate)] = $netCarb[4]-$netCarb[5];
    $currentDate = strtotime(date('Y-m-d', $currentDate) . ' +1 day');
    }
    var_dump($netCarbs);
}


 ?>

<form method="POST">
    <label>Username
    <input name="username" type="text" value="<?php echo $_POST["username"]; ?>"></label><br>
    <label>Start Date
    <input name="start_date" type="date" value="<?php echo $_POST["start_date"]; ?>"></label><br>
    <label>End Date
    <input name="end_date" type="date" value="<?php echo $_POST["end_date"]; ?>"></label><br>
    <input type="submit" name="submit" value="submit">
</form>
