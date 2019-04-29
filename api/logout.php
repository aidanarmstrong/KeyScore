<?php
Session_start();
Session_unset();
Session_destroy();

header("Location: ../../login.html");