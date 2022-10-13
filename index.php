<?php

require_once 'connec.php';


$pdo = new \PDO(DSN, USER, PASS);

// A exécuter afin de tester le contenu de votre table friend
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);

// On veut afficher notre résultat via un tableau associatif (PDO::FETCH_ASSOC)
$friendsArray = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Liste des friends :</h1>
    <ul>
        <?php foreach ($friendsArray as $friend) : ?>
            <li><?= $friend['firstname'] . ' ' . $friend['lastname'] ?></li>
        <?php endforeach ?>
    </ul>
    <h2>Ajoute un friend :</h2>
    <form action="" method="POST">
        <label for="firstname">Prénom :</label>
        <input type="text" name="firstname">

        <label for="lastname">Nom :</label>
        <input type="text" name="lastname">

        <button>Ajoute le friend</button>
    </form>
    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newFriend = array_map('trim', $_POST);
        $errors = [];

        if (empty($newFriend['firstname'])) {
            $errors[] = 'Veuillez entrer un prénom.';
        };

        if (empty($newFriend['lastname'])) {
            $errors[] = 'Veuillez entrer un nom de famille.';
        };

        if (strlen($newFriend['firstname']) > 45) {
            $errors[] = 'Le prénom ne peut pas dépasser 45 caractères.';
        }

        if (strlen($newFriend['lastname']) > 45) {
            $errors[] = 'Le nom ne peut pas dépasser 45 caractères.';
        }

        if (empty($errors)) {

            $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
            $statement = $pdo->prepare($query);

            $statement->bindValue(':firstname', $newFriend['firstname'], \PDO::PARAM_STR);
            $statement->bindValue(':lastname', $newFriend['lastname'], \PDO::PARAM_STR);
            $statement->execute();

            header('Location: /');
        };
    }
    ?>
    <ul>
        <?php

        if (!empty($errors)) :   ?>
            <?php foreach ($errors as $error) : ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
    </ul>
<?php endif; ?>

</body>

</html>