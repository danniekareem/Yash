<?php

function auth()
{
    return isset($_SESSION['user']);
}


function redirect($path)
{
    header("Location: " . ROOT . "/" . $path);
    die;
}


function require_auth()
{
    if (!auth()) {
        redirect('Auth');
    }
}


function esc($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
