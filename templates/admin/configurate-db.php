<form action="" method="POST" id="configurate-form">
  <div class="form__group">
    <label for="application_name" class="form__label">Имя приложения</label>
    <input class="form__input" id="application_name" name="application_name" placeholder="Example Project" required type="text">
  </div>

  <fieldset class="form__fieldset">
    <legend class="form__legend">Подключение к базе данных</legend>

    <div class="form__group">
      <label for="connection_host" class="form__label">Хост базы данных</label>
      <input class="form__input" id="connection_host" name="connection_host" placeholder="localhost" required type="text">
    </div>

    <div class="form__group">
      <label for="connection_user" class="form__label">Пользователь базы данных</label>
      <input class="form__input" id="connection_user" name="connection_user" required type="text">
    </div>

    <div class="form__group">
      <label for="connection_password" class="form__label">Пароль базы данных</label>
      <input class="form__input" id="connection_password" name="connection_password" required type="password">
    </div>

    <div class="form__group">
      <label for="connection_database" class="form__label">Имя базы данных</label>
      <input class="form__input" id="connection_database" name="connection_database" required type="text">
    </div>

    <div class="form__group">
      <label for="connection_prefix" class="form__label">Префикс таблиц</label>
      <input class="form__input" id="connection_prefix" name="connection_prefix" required type="text" value="lcms">
      <p class="form__help-text">
        Каждая таблица в БД будет иметь вид &lt;приставка&gt;_&lt;имя таблицы&gt;
      </p>
    </div>
  </fieldset>

  

  <div class="form__submit-container col-md-2">
    <button class="btn btn-primary form__submit">Поехали!</button>
  </div>
</form>
