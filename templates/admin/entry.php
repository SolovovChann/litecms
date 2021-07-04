<a href="<?= url(Litecms\Controllers\Admin::class, "table", [$entry::$table]) ?>" class="btn btn-primary">&larr; Назад к списку записей</a>

<form action="" class="form" method="POST">
  <? foreach ($fields as $key => $value): ?>
  <div class="form__group">
    <label for="<?= $key ?>" class="form__label"><?= $key ?></label>

    <? if ($value->mmetype === 'select'): ?>
      <select name="<?= $key ?>" id="<?= $key ?>" class="form__input">
        <option value="">Select <?= $key ?></option>
        <? foreach ($value->reference::all() as $entry): ?>
          <option value="<?= $entry->id ?>"><?= $entry ?></option>
        <? endforeach; ?>
      </select>

    <? elseif ($value->mmetype === 'textarea'): ?>
      <textarea name="<?= $key ?>" id="<?= $key ?>" class="form__input"><?= $entry->$key ?></textarea>
    <? else: ?>
    
      <input
        class="form__input"
        id="<?= $key ?>"
        <? if (isset($value->max)): ?>
  maxlength="<?= $value->max ?>"
        <? endif; ?>
        name="<?= $key ?>"
        type="<?= $value->mmetype ?>"
        <? if (isset($entry->$key)): ?>
  value="<?= $entry->$key ?>"
        <? endif; ?>
      >
    </div>
  <? endif; ?>
  <? endforeach; ?>

  <div class="form__submit-container">
    <button type="submit" class="btn btn-success form__submit">Отправить</button>
  </div>
</form>