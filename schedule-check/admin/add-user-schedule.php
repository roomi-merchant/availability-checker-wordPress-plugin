<?php

global $wpdb, $table_prefix;

$table_name = $table_prefix . 'schedule_check';

if (isset($_POST['submit'])) {
    $name = sanitize_text_field($_POST['username']);
    $cnic = sanitize_text_field($_POST['cnic']);
    $start_time = sanitize_text_field($_POST['start_time']);
    $end_time = sanitize_text_field($_POST['end_time']);

    $data = array(
        'Name' => $name,
        'CNIC' => $cnic,
        'Start Time' => $start_time,
        'End Time' => $end_time,
    );

    $wpdb->insert($table_name, $data);
}
?>

<div class="wrap">
    <h3>Add Schedule</h3>
    <form action="<?php echo esc_url(get_the_permalink()); ?>" method="POST">
        <div class="fields">
            <input type="text" name="username" placeholder="Full Name">
            <input type="number" name="cnic" placeholder="CNIC Number">
            <input type="time" name="start_time" placeholder="Start Time">
            <input type="time" name="end_time" placeholder="End Time">
        </div>
        <div class="btns" style="line-height: 4;">
            <input type="submit" name="submit" value="SUBMIT" class="page-title-action">
            <input type="submit" name="submit" value="Add+" class="page-title-action">
        </div>
    </form>
</div>