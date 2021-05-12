<?php
require_once 'pdo.php';

if (isset($_POST['student_id']) && isset($_POST['game_nm_value'])) {
    $sql = 'UPDATE Student
                    SET game_name = :game_name
                    WHERE  student_id =:student_id';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':student_id' => htmlentities($_POST['student_id']),
        ':game_name' => htmlentities($_POST['game_nm_value']),
    ]);
}
?>





