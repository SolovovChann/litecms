<a href="<?= url(Litecms\Controllers\Admin::class, "default") ?>" class="btn btn-primary">&larr; К списку таблиц</a>
<a href="<?= url(Litecms\Controllers\Admin::class, "create", [$name]) ?>" class="btn btn-success">Добавить</a>

<div class="main__resizable-x">
  <table class="table">
    <tr>
      <? foreach($head as $column): ?>
        <th><?= $column['COLUMN_NAME'] ?></th>
      <? endforeach; ?>
      <th colspan="2">Управление</th>
    </tr>
    <? if (!empty($table)): ?>
      <? foreach($table as $row): ?>
        <tr>
          <? foreach ($row as $column): ?>
            <td><?= $column ?></td>
          <? endforeach; ?>
          <td>
            <a href="<?= url(Litecms\Controllers\Admin::class, "delete", [$name, $row['id']]) ?>" class="btn btn-danger">Удалить</a>
          </td>
          <td>
            <a href="<?= url(Litecms\Controllers\Admin::class, "edit", [$name, $row['id']]) ?>" class="btn btn-warning">Изменить</a>
          </td>
        </tr>
      <? endforeach; ?>
    <? else: ?>
      <tr>
        <td colspan="<?= count($head) + 1 ?>">Записей в таблице нет</td>
      </tr>
    <? endif; ?>
  </table>
</div>