<h2>Создать базу данных</h2>
<a href="<?= url(\Litecms\Controllers\Admin::class, "createDB") ?>" class="btn btn-primary">Создать базу данных</a>

<h2>Зарегистрированные модели</h2>
<a href="<?= url(\Litecms\Controllers\Admin::class, "createModel") ?>" class="btn btn-primary">&plus; Новая модель</a>
<ol>
	<? foreach($models as $model): ?>
		<li>
			<a href="<?= url(Litecms\Controllers\Admin::class, "table", [$model::$table]) ?>"><?= $model::$plural ?></a>
		</li>
	<? endforeach; ?>
</ol>

<h2>Разегистрированные страницы</h2>
<table class="table">
  <tr>
    <th>URL адрес</th>
    <th>Функция-обработчик</th>
    <th>Доступные методы</th>
  </tr>
  <tr>
    <? foreach($routes as $route): ?>
      <tr>
        <td><?= $route['url'] === '' ? '<Основная страница>' : $route['url']  ?></td>
        <td><?= gettype ($route['view']) === 'object' ? "Функция callback" : implode('::', $route['view']) ?></td>
        <td><?= implode(", ", $route['methods']) ?></td>
      </tr>
    <? endforeach; ?>
  </tr>
</table>