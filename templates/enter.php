<div class="container">
    <div class="row justify-content-center mt-3">
        <div class="col-4">
            <form action="index.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input id="email" type="email" name="email" class="form-control <?= getClassValidInput($errors, 'email') ?>" value="<?= getValueForm('email') ?>">
                    <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input id="password" type="password" name="password" class="form-control <?= getClassValidInput($errors, 'password') ?>" value="<?= getValueForm('password') ?>">
                    <div class="invalid-feedback"><?= $errors['password'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <input type="submit" value="Войти" class="btn btn-primary" name="enter">
                </div>
            </form>
        </div>
        <div class="col-2 text-center">
            <a href="registration.php">Регистрация</a>
        </div>
    </div>
</div>