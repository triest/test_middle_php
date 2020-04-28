<? if ($transaction != null) {
    echo $transaction;
} ?>

<?= $balance ?>

<form action="/" method="post" class="form-horizontal">
    <label>Вывести</label>
    <input type="number" id="money" name="money">
    <input type="submit" value="Вывести">
</form>
