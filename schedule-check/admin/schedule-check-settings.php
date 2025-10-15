<?php

global $wpdb, $table_prefix;

$table_name = $table_prefix . 'schedule_check';

$q = "SELECT * FROM {$table_name}";
$result = $wpdb->get_results($q);

date_default_timezone_set('Asia/Karachi');
$current_time = date('H:i:s');

if (isset($_POST['check_available'])) {
    $check_available = sanitize_text_field($_POST['check_available']);
    $checkAvailabilitySql = $wpdb->prepare("
    SELECT *
    FROM wp_schedule_check
    WHERE TIME(%s) BETWEEN TIME(`Start Time`) AND TIME(`End Time`)
", $current_time);
    $result_check_availability = $wpdb->get_results($checkAvailabilitySql);
}

ob_start();
?>

<div class="wrap">
    <h2>All Schedules</h2>
    <div class="text-right">
        <input type="text" name="search" placeholder="Search...">
        <input type="submit" name="submit" value="SUBMIT" class="page-title-action">
    </div>
    <form action="<?php echo esc_url(get_the_permalink()); ?>" method="POST">
        <p><input type="submit" name="check_available" value="Check Who's Available Right Now" class="page-title-action"></p>
    </form>


    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <th>Name</th>
            <th>CNIC</th>
            <th>Start Time</th>
            <th>End Time</th>
        </thead>
        <tbody>
            <?php
            if (!isset($_POST['check_available'])) {
                foreach ($result as $row) { ?>
                    <tr>
                        <td><?php echo $row->Name; ?></td>
                        <td><?php echo $row->CNIC; ?></td>
                        <td><?php echo $row->{'Start Time'}; ?></td>
                        <td><?php echo $row->{'End Time'}; ?></td>
                    </tr>
                <?php }
            } else {
                foreach ($result_check_availability as $rowAvailable) { ?>
                    <tr>
                        <td><?php echo $rowAvailable->Name; ?></td>
                        <td><?php echo $rowAvailable->CNIC; ?></td>
                        <td><?php echo $rowAvailable->{'Start Time'}; ?></td>
                        <td><?php echo $rowAvailable->{'End Time'}; ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<?php
echo ob_get_clean();
?>