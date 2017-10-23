<?php
if(count($errors) > 0) {
    foreach ($errors as $error) {
        ?>

        <p class="text-danger"><?php echo $error; ?></p>

        <?php
    }
}
?>

<?php
if(Session::exists('success')) {
    ?>

    <p class="text-success"><?php echo Session::flash('success'); ?></p>

    <?php
}
?>