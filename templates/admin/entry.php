<a href="<?= url(Litecms\Controllers\Admin::class, "table", [$entry::$table]) ?>" class="btn btn-primary">&larr; Назад к списку записей</a>

<form action="" class="form" method="POST">
  <? foreach ($entry as $key => $value): ?>
  <div class="form__group">
    <label for="<?= $key ?>" class="form__label"><?= $key ?></label>
    <input type="text" name="<?= $key ?>" value="<?= $value ?>" id="<?= $key ?>" class="form__input">
  </div>
  <? endforeach; ?>

  <div class="form__submit-container">
    <button type="submit" class="btn btn-success form__submit">Отправить</button>
  </div>
</form>