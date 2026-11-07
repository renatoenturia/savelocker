<?php

unset($_SESSION['auth']);
limparOld();

header('Location: /login');
exit();
