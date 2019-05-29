<?php

include_once '../config/database.php';
include_once '../model/submission.php';
include_once '../controller/submission.php';

if (isset($_POST['update'])) {
    $database = new Database();
    $conn = $database->getConn();

    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if (empty($id) || empty($status)) {
        echo json_encode(array("success" => false, "message" => "Dados insuficientes. Tente novamente mais tarde"));
        exit;
    }

    $submissionController = new SubmissionController();
    $submission = new Submission(null, $id, null, null, null, null, null, null, null, $status);
    $success = $submissionController->updateSubmission($submission, $conn);

    echo json_encode(array("success" => true));
    exit;
}
