<h2>Создать базу данных</h2>
<a href="/admin/createdb" class="btn btn-primary">Создать базу данных</a>

<h2>Зарегистрированные модели</h2>
<ol>
	<? foreach($models as $model): ?>
		<li>
			<a href="<?= url(Litecms\Controllers\Admin::class, "table", [$model::$table]) ?>"><?= $model::$plural ?></a>
		</li>
	<? endforeach; ?>
</ol>