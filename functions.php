<?php

use SbWereWolf\XmlNavigator\NavigatorFabric;

/**
 * Валидация xml-файла
 * @param string $field
 * 
 * @return string|null
 */
function validateXmlFile(string $field) : ?string
{
	if (!empty($_FILES[$field]['name'])) {
		$typeFile = $_FILES[$field]['type'];
        $nameFile = $_FILES[$field]['name'];  
        $extensionFile = ( new SplFileInfo($nameFile) )->getExtension();

        if ($typeFile !== 'text/xml' || $extensionFile !== 'xml') {
            return 'Файл должен соответствовать формату xml';
        }

	} else {
        return "Файл не выбран";

    }

    return null;
}


/**
 * Добавить владельца
 * @param PDO $pdo
 * @param string $ownerName
 * 
 * @return string
 */
function insertOwner(PDO $pdo, string $ownerName) : string
{
    $stmt = $pdo->prepare("INSERT INTO `owners` (`name`) VALUES (:name)");
    $stmt->execute([':name' => $ownerName]);  
    $ownerId = $pdo->lastInsertId();

    return $ownerId;
}


/**
 * Проверка существования типа питомца
 * @param PDO $pdo
 * @param string $type
 * 
 * @return array
 */
function issetTypePet($pdo, $type) : array
{
    $stmt = $pdo->prepare("SELECT * FROM `type_pets` WHERE `name` = :type");
    $stmt->execute([':type' => $type]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result : [];
}


/**
 * Добавить тип питомца
 * @param PDO $pdo
 * @param string $type
 * 
 * @return string
 */
function insertTypePet($pdo, $type) : string
{
    $stmt = $pdo->prepare("INSERT INTO `type_pets` (`name`) VALUES (:type)");
    $stmt->execute([':type' => $type]);  
    $typeId = $pdo->lastInsertId();

    return $typeId;
}


/**
 * Получить id гендера
 * @param PDO $pdo
 * @param string $gender
 * 
 * @return string
 */
function getGenderId($pdo, $gender) : string
{
    $stmt = $pdo->prepare("SELECT * FROM `genders` WHERE `name` = :name");
    $stmt->execute([':name' => $gender]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $genderId = $result['id'];

    return $genderId;
}


/**
 * Проверка существования породы питомца
 * @param PDO $pdo
 * @param string $breed
 * 
 * @return array
 */
function issetBreed($pdo, $breed) : array
{
    $stmt = $pdo->prepare("SELECT * FROM `breeds` WHERE `name` = :breed");
    $stmt->execute([':breed' => $breed]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result : [];
}


/**
 * Добавить породу питомца
 * @param PDO $pdo
 * @param string $breed
 * 
 * @return string
 */
function insertBreed($pdo, $breed) : string
{
    $stmt = $pdo->prepare("INSERT INTO `breeds` (`name`) VALUES (:breed)");
    $stmt->execute([':breed' => $breed]);  
    $breedId = $pdo->lastInsertId();  

    return $breedId;
}


/**
 * Добавить питомца
 * @param PDO $pdo
 * @param string $code
 * @param string $nickname
 * @param string $breedId
 * @param string $typeId
 * @param string $genderId
 * @param string $age
 * @param string $ownerId
 * 
 * @return string
 */
function insertPet($pdo, $code, $nickname, $breedId, $typeId, $genderId, $age, $ownerId) : string
{
    $stmt = $pdo->prepare("INSERT INTO `pets` (`code`, `nickname`, `breed_id`, `type_id`, `gender_id`, `age`, `owner_id`) 
                                        VALUES (:code, :nickname, :breed_id, :type_id, :gender_id, :age, :owner_id)");
    $stmt->execute([
        ':code' => $code, 
        ':nickname' => $nickname, 
        ':breed_id' => $breedId, 
        ':type_id' => $typeId, 
        ':gender_id' => $genderId, 
        ':age' => $age, 
        ':owner_id' => $ownerId                           
    ]);  

    $petId = $pdo->lastInsertId(); 
    
    return $petId;
}


/**
 * Проверка существования награды
 * @param PDO $pdo
 * @param string $nameReward
 * 
 * @return array
 */
function issetReward($pdo, $nameReward) : array
{
    $stmt = $pdo->prepare("SELECT * FROM `rewards` WHERE `name` = :reward");
    $stmt->execute([':reward' => $nameReward]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result : [];
}


/**
 * Добавить награду
 * @param PDO $pdo
 * @param string $nameReward
 * 
 * @return string
 */
function insertReward($pdo, $nameReward) : string
{
    $stmt = $pdo->prepare("INSERT INTO `rewards` (`name`) VALUES (:reward)");
    $stmt->execute([':reward' => $nameReward]);  
    $rewardId = $pdo->lastInsertId(); 

    return $rewardId;
}


/**
 * Добавить связь питомца с наградой
 * @param PDO $pdo
 * @param string $petId
 * @param string $rewardId
 */
function insertPetsRewards($pdo, $petId, $rewardId) : void
{
    $stmt = $pdo->prepare("INSERT INTO `pets_rewards` (`pet_id`, `reward_id`) VALUES (:pet_id, :reward_id)");
    $stmt->execute([
        ':pet_id' => $petId,
        ':reward_id' => $rewardId                       
    ]); 
}


/**
 * Добавить родителя
 * @param PDO $pdo
 * @param string $codeParent
 * @param string $petId
 */
function insertParents($pdo, $codeParent, $petId) : void
{
    $stmt = $pdo->prepare("INSERT INTO `parents` (`parent_code`, `pet_id`) VALUES (:parent_code, :pet_id)");
    $stmt->execute([
        ':parent_code' => $codeParent, 
        ':pet_id' => $petId
    ]);
}


/**
 * Прочитать xml файл и внести данные в базу
 * @param string $file
 * @param PDO $pdo
 */
function readXmlFileAndWriteDb($file, $pdo) : void
{
    $content = file_get_contents($file);
    $fabric = (new NavigatorFabric())->setXml($content);
    $converter = $fabric->makeConverter();
    $inputData = $converter->toArray();
    
    if (array_key_exists('*multiple', $inputData['Users']['*elements']['User'])) {
        // владельцев питомцев > 1
        $owners = $inputData['Users']['*elements']['User']['*multiple'];
    } else {
        $owners = [$inputData['Users']['*elements']['User']];
    }

    foreach($owners as $owner) {
        $ownerName = $owner['*attributes']['Name'];

        if (array_key_exists('*multiple', $owner['*elements']['Pets']['*elements']['Pet'])) {
            // питомцев > 1
            $pets = $owner['*elements']['Pets']['*elements']['Pet']['*multiple'];
        } else {
            $pets = [$owner['*elements']['Pets']['*elements']['Pet']];
        }

        try {
            $pdo->beginTransaction(); // начало транзакции
            $ownerId = insertOwner($pdo, $ownerName);

            foreach($pets as $pet) {
                $code = $pet['*attributes']['Code'];
                $type = $pet['*attributes']['Type'];

                // проверим, существует ли такой тип в БД
                $result = issetTypePet($pdo, $type);

                if (empty($result)) {
                    $typeId = insertTypePet($pdo, $type);
                } else {
                    $typeId = $result['id'];
                }

                $gender = $pet['*attributes']['Gender'];
                $gender = ($gender === 'm') ? 'м' : $gender;
                // вытащим id гендера
                $genderId = getGenderId($pdo, $gender);

                $age = $pet['*attributes']['Age'];
                $breed = $pet['*elements']['Breed']['*attributes']['Name'];

                // проверим, существует ли такая порода в БД
                $result = issetBreed($pdo, $breed);

                if (empty($result)) {
                    $breedId = insertBreed($pdo, $breed);
                } else {
                    $breedId = $result['id'];
                }

                $nickname = $pet['*elements']['Nickname']['*attributes']['Value'];
                $petId = insertPet($pdo, $code, $nickname, $breedId, $typeId, $genderId, $age, $ownerId);                           

                if (array_key_exists('Rewards', $pet['*elements'])) {
                    $rewards = array_key_exists('*multiple', $pet['*elements']['Rewards']['*elements']['Reward']) ? 
                            $pet['*elements']['Rewards']['*elements']['Reward']['*multiple'] : 
                            [$pet['*elements']['Rewards']['*elements']['Reward']];
                } else {
                    $rewards = [];
                }

                foreach($rewards as $reward) {
                    $nameReward = $reward['*attributes']['Name'];
                    // проверим, существует ли такая награда в БД
                    $result = issetReward($pdo, $nameReward);

                    if (empty($result)) {
                        $rewardId = insertReward($pdo, $nameReward);
                    } else {
                        $rewardId = $result['id'];
                    }         

                    insertPetsRewards($pdo, $petId, $rewardId);
                }

                if (array_key_exists('Parents', $pet['*elements'])) {
                    $parents = array_key_exists('*multiple', $pet['*elements']['Parents']['*elements']['Parent']) ? 
                            $pet['*elements']['Parents']['*elements']['Parent']['*multiple'] : 
                            [$pet['*elements']['Parents']['*elements']['Parent']];
                } else {
                    $parents = [];
                }

                foreach($parents as $parent) {
                    $codeParent = $parent['*attributes']['Code'];
                    insertParents($pdo, $codeParent, $petId);
                }
            } 

            $pdo->commit(); // конец транзакции
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Ошибка: " . $e->getMessage();
        }
    }
}


/**
 * Проверка существования пользователя
 * @param PDO $pdo
 * @param string $email
 * 
 * @return array
 */
function issetUser($pdo, $email) : array
{
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email");
    $stmt->execute([':email' => $email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result : [];
}


/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name путь к файлу шаблона относительно папки templates
 * @param array $data ассоциативный массив с данными для шаблона
 * @return string итоговый HTML
 */
function includeTemplate(string $name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}


/**
 * Проверяет существование пользователя в базе и добавляет его, если нету
 * @param PDO pdo
 * @param array $errors
 * @param array $form
 * 
 * @return bool
 */
function addUser(PDO $pdo, array &$errors, array $form) : bool 
{
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email");
    $stmt->execute([':email' => $form['email']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    } elseif ($form['password'] !== $form['password-repeat']) {
        $errors['password-repeat'] = 'Повтор пароля введен неверно';
    } else {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)");
        $stmt->execute([
            ':email' => $form['email'], 
            ':password' => $password
        ]);

        return true;
    } 

    return false;
}

/**
 * Все владельцы, у которых есть питомцы, старше 3 лет
 * @param PDO $pdo
 * 
 * @return array
 */
function selectPetAge3(PDO $pdo) : array
{
    $query = 
        "SELECT 
            * 
        FROM 
            `owners` `o`
            JOIN `pets` `p` ON `p`.`owner_id` = `o`.`id`
        WHERE 
            `p`.`age` > 3;";

    $stmt = $pdo->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ? $result : [];
}


/**
 * @param array $errors
 * @param string $field
 * 
 * @return string
 */
function getClassValidInput($errors, $field) : string  
{
    if (isset($_POST['doUpload']) || isset($_POST['doReg']) || isset($_POST['enter'])) {
        if (isset($errors[$field])) {
            return "is-invalid";
        }
    }

    return "";
}


/**
 * @param string $field
 * 
 * @return string
 */
function getValueForm($field) : string
{
    if (isset($_POST['doUpload']) || isset($_POST['doReg']) || isset($_POST['enter'])) {
        if (isset($_POST[$field])) {
            return $_POST[$field];
        }
    }

    return "";
}

