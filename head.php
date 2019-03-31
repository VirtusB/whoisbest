<?php

require_once 'php/core/init.php';

$user = new User();

if (isset($_GET['login'])) {
    Auth::Login();
}

if (isset($_GET['logout'])) {
    Auth::Logout();
}

if (isset($_GET['update'])) {
    Auth::Update();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Who Is Best</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/darkster-theme.css">
    <link rel="stylesheet" href="/assets/css/fontawesome.all.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>


<body data-spy="scroll" data-target="#navbar1" data-offset="60">