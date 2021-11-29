<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $curDirPath     = dirname(__FILE__);
    require_once("$curDirPath/../../includes/defines.php");
    require("$curDirPath/../includes/config.inc.php");
    require(MYSQL);

    session_start();

    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name']))
    {
        $userId   = $_SESSION['user_id'];
        $producer = mysqli_real_escape_string($dbc, $_POST['producer']);
        $action   = $_POST['action'];

        if ($action == 'add')
        {
            mysqli_query($dbc, "CALL add_producer_to_favorite_list('$userId', '$producer')");
        }
        else if ($action == 'remove')
        {
            mysqli_query($dbc, "CALL remove_producer_from_favorite_list('$userId', '$producer')");
        }
    }
}

?>
