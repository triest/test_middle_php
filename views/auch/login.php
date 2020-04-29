<form action="/auch" method="post" class="form-horizontal">
    <? if (isset($error) && $error != null) {
        echo $error;
        echo "<br>";
    }
    ?>
    <div class="form-group">
        <label for="email">Логин:</label>
        <input type="text" id="email" name="email" >  triest
    </div>
    <div class="form-group">
        <label for="password">Пароль:</label>
        <input type="text" id="password" name="password" > password
    </div>
    <button type="submit" class="btn btn-primary">Войти</button>

    <a href="/">Назад</a>
</form>