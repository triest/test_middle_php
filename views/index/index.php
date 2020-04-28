

<br>
Баланс:
<?= $balance ?>
<br>
<form action="/" method="post" class="form-horizontal" novalidate>
    <label>Вывести</label>
    <input type="hidden" name="csrf_token" value="<?php echo generateToken('protectedForm'); ?>"/>
    <input type="number" id="money" name="money">
    <input type="submit" value="Вывести">
    <? if ($transaction != null) {
        echo $transaction;
    } ?>
</form>
