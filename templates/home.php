<? include "components/head.php" ?>
<? include "components/header.php" ?>

<h1><?= $title ?></h1>

<p>
	Вы отослали <?= $_SERVER['REQUEST_METHOD'] ?> запрос.
</p>

<form action="/home/test/" type="post">
	<input type="text" placeholder="Имя" name="username" value="Johnny">
	<input type="number" placeholder="Возраст" name="age" value="28">
	<input type="file" name="file">

	<button type="submit">Отправить</button>
</form>


<? include "components/footer.php" ?>