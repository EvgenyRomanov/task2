<div class="container">
    <div class="row justify-content-center mt-3">
        <div class="col-4">
            <form action="download.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="myFile" class="form-label">Выберите xml-файл</label>
                    <input class="form-control <?= getClassValidInput($errors, 'myFile') ?>" id="myFile" type="file" name="myFile">
                    <div class="invalid-feedback"><?= $errors['myFile'] ?? '' ?></div>
                </div>
                <input class="btn btn-primary mb-3" type="submit" name="doUpload" value="Закачать">
            </form>
        </div>
        <div class="col-2 text-center">
            <a href="logout.php">Выйти</a>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
        <h4>Все владельцы, у которых есть питомцы, старше 3 лет:</h4>    
            <ul>
                <?php foreach($users as $user):?>
                    <li><?= $user['name'] ?></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>

