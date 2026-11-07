<?php

if (! auth()) {
    header('Location: /login');
    exit();
}

header('Location: /dashboard');
exit();
