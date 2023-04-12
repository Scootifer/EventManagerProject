<?php
// Set timezone
date_default_timezone_set('America/New_York');

// Get current month and year
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

// Get number of days in the month
$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Create date object for the first day of the month
$date = new DateTime("$year-$month-01");

// Determine the day of the week of the first day
$dayOfWeek = $date->format('N');

// Create table for the calendar
echo '<table>';
echo '<tr><th colspan="7">' . $date->format('F Y') . '</th></tr>';
echo "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr> \n";

// Create blank cells for days before the first day of the month
echo '<tr>';
for ($i = 1; $i < $dayOfWeek; $i++) {
    echo '<td></td>';
}

// Create cells for each day of the month
for ($i = 1; $i <= $numDays; $i++) {
    echo '<td>' . $i . '</td>';
    if (($i + $dayOfWeek - 1) % 7 == 0) {
        echo "</tr><tr> \n";
    }
}

// Create blank cells for days after the last day of the month
for ($i = ($numDays + $dayOfWeek - 1) % 7; $i < 7; $i++) {
    echo '<td></td>';
}
echo "</tr> \n";
echo '</table>';
?>
