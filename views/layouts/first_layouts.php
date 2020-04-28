<html>
<head>
    <title>Тестовое задание</title>
    <script src="formNumber.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
</head>

<body>

<div class="container">
    <div class="row">
        <?
            if ($_SESSION['auth_user'] == "" || $_SESSION['auth_user'] == null) {
                ?>
                <input type="button" class="btn btn-primary" onclick="window.location.href = '/auch';" value="Войти"/>
                <?
            } else {
                ?>
                <a href="/auch?action=logout">Выйти</a>
                <?
            }
        ?>
        <?php
            include($contentPage);
        ?>
    </div>
</div>
</body>
</html>