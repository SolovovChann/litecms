<?= debug($_POST) ?>

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
      <input class="form__input" id="connection_user" name="connection_user" value="root" required type="text">
    </div>

    <div class="form__group">
      <label for="connection_password" class="form__label">Пароль базы данных</label>
      <input class="form__input" id="connection_password" name="connection_password" placeholder="Надёжный и сильный" required type="password">
    </div>

    <div class="form__group">
      <label for="connection_database" class="form__label">Имя базы данных</label>
      <input class="form__input" id="connection_database" name="connection_database" placeholder="litecms" required type="text">
    </div>

    <div class="form__group">
      <label for="connection_prefix" class="form__label">Префикс таблиц</label>
      <input class="form__input" id="connection_prefix" name="connection_prefix" required type="text" value="lcms">
      <p class="form__help-text">
        Каждая таблица в БД будет иметь вид &lt;приставка&gt;_&lt;имя таблицы&gt;
      </p>
    </div>
  </fieldset>

  <fieldset class="form__fieldset">
    <legend class="form__legend">Создание администратора</legend>
    <div class="form__group">
      <label for="admin_username" class="form__label">Логин администратора</label>
      <input class="form__input" id="admin_username" name="admin_username" placeholder="admin" required type="text">
    </div>

    <div class="form__group row">
      <div class="col-md-6 form__group-inline">
        <label for="admin_first_name" class="form__label">Имя администратора</label>
        <input class="form__input" id="admin_first_name" name="admin_first_name" placeholder="Иван" required type="text">
      </div>

      <div class="col-md-6 form__group-inline">
        <label for="admin_last_name" class="form__label">Фамилия администратора</label>
        <input class="form__input" id="admin_last_name" name="admin_last_name" placeholder="Иванов" required type="text">
      </div>
    </div>

    <div class="form__group">
      <label for="admin_email" class="form__label">Email администратора</label>
      <input class="form__input" id="admin_email" name="admin_email" placeholder="ivan.ivanov@mail.ru" required type="email">
    </div>

    <div class="form__group">
      <label for="admin_password" class="form__label">Придумайте пароль</label>
      <input class="form__input" id="admin_password" name="admin_password" placeholder="Надёжный и сильный" required type="password">
      <div class="form__help-text">
        Пароль должен содержать минимум одну:
          <ol>
            <li>Строчную букву</li>
            <li>Заглавную букву</li>
            <li>Цифру</li>
            <li>Специальный символ</li>
          </ol> 
      </div>
    </div>

    <div class="form__group">
      <div style="display: flex; justify-content: flex-start; align-items: center">
        <input type="checkbox" name="admin_weak_password" id="admin_weak_password" style="margin-right: 1.25em;">
        <label for="admin_weak_password" class="form__label">Я буду использовать слабый пароль и я знаю, что делаю!</label>
      </div>
      <p class="form__help-text">
        Отключение проверки безопасности пароля может привести к взлому базы данных!
      </p>
    </div>

    <div class="form__group">
      <label for="admin_password_verify" class="form__label">Повторите пароль</label>
      <input class="form__input" id="admin_password_verify" name="admin_password_verify" placeholder="Пароли должны совпадать" required type="password">
    </div>
  </fieldset>

  <div class="form__submit-container col-md-2">
    <button class="btn btn-primary form__submit">Поехали!</button>
  </div>
</form>

<script>
  const form = document.getElementById('configurate-form')

  function verifyPassword(password)
  {
    if (password === "") {
      alert("Заполните поле пароль")
      return false
    }

    if (password.length < 6) {
      alert("Пароль должен быть более 6 символов")
      return false
    }
  }

  form.submit = function()
  {
    let email = document.querySelector('input[name=admin_email]').value
    let password = document.querySelector('input[name=admin_password]').value
    let verify = document.querySelector('input[name=admin_password_verify]').value
    let allowWeakPassword = document.querySelector('input[name=admin_weak_password]').checked

    if (password !== verify) {
      alert("Пароли не совпадают!")
      return false
    }

    if (email.match(/[\w.\\]+\@\w+\.\w+/) === null) {
      alert("Email введён неправильно")
      return false
    }

    if (allowWeakPassword === false) {
      let result = verifyPassword(password)
      if (result === false) {
        return false
      }
    }

    return true
  }
</script>