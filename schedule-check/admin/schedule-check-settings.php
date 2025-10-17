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
    FROM {$table_name}
    WHERE TIME(%s) BETWEEN TIME(`Start Time`) AND TIME(`End Time`)
", $current_time);
    $result_check_availability = $wpdb->get_results($checkAvailabilitySql);
}

if (isset($_GET['search'])) {
    $search = sanitize_text_field($_GET['search']);
    $searchSql = $wpdb->prepare(
        "SELECT * FROM {$table_name} WHERE `Name` LIKE %s",
        '%' . $wpdb->esc_like($search) . '%'
    );
    $search_result = $wpdb->get_results($searchSql);
}

ob_start();
?>

<div class="wrap">
    <h2>All Schedules</h2>
    <form action="<?php echo esc_url(admin_url('admin.php')); ?>" method="GET">
        <div class="text-right">
            <input type="hidden" name="page" value="schedule-check">
            <input type="text" name="search" placeholder="Search...">
            <input type="submit" value="SUBMIT" class="page-title-action">
        </div>
    </form>
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
                if (isset($_GET['search'])) {
                    foreach ($search_result as $search_row) { ?>
                        <tr>
                            <td><?php echo esc_attr($search_row->Name); ?></td>
                            <td><?php echo esc_attr($search_row->CNIC); ?></td>
                            <td><?php echo esc_attr($search_row->{'Start Time'}); ?></td>
                            <td><?php echo esc_attr($search_row->{'End Time'}); ?></td>
                        </tr>
                    <?php }
                } else {
                    foreach ($result as $row) { ?>
                        <tr>
                            <td><?php echo esc_attr($row->Name); ?></td>
                            <td><?php echo esc_attr($row->CNIC); ?></td>
                            <td><?php echo esc_attr($row->{'Start Time'}); ?></td>
                            <td><?php echo esc_attr($row->{'End Time'}); ?></td>
                        </tr>
                    <?php
                    }
                }
            } else {
                foreach ($result as $row) { ?>
                    <tr>
                        <td><?php echo esc_attr($row->Name); ?></td>
                        <td><?php echo esc_attr($row->CNIC); ?></td>
                        <td><?php echo esc_attr($row->{'Start Time'}); ?></td>
                        <td><?php echo esc_attr($row->{'End Time'}); ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php
if (isset($_POST['check_available'])) { ?>
    <div class="popup available_popup">
        <div class="popup_wrapper">
            <a href="javascript:void(0)" class="close_popup">X</a>
            <table class="table-view-list striped posts">
                <h2>Available Staff</h2>
                <?php foreach ($result_check_availability as $rowAvailable) { ?>
                    <tr>
                        <td><?php echo esc_attr($rowAvailable->Name); ?></td>
                        <td><?php echo esc_attr($rowAvailable->CNIC); ?></td>
                        <td><?php echo esc_attr($rowAvailable->{'Start Time'}); ?></td>
                        <td><?php echo esc_attr($rowAvailable->{'End Time'}); ?></td>
                    </tr>
                <?php
                } ?>
            </table>
        <?php } ?>
        </div>
    </div>
    <?php
    echo ob_get_clean();
    ?>