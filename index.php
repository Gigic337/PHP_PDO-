<?php
require_once 'connec.php';

$pdo = new \PDO(DSN, USER, PASS);

$errors = [];
$firstname = '';
$lastname = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    if (!isset($firstname) || trim($firstname) === '') {
        $errors[] = 'Firstname is required';
    }

    if (!isset($lastname) || trim($lastname) === '') {
        $errors[] = 'Lastname is required';
    }

    if (empty($errors)) {
        $firstname = htmlspecialchars(trim($firstname));
        $lastname = htmlspecialchars(trim($lastname));


        $sql = "INSERT INTO friend(firstname, lastname) VALUE (:firstname, :lastname)";
        $statement = $pdo->prepare($sql);

        $statement->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, PDO::PARAM_STR);

        $statement->execute();

        header('Location: index.php');
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Friends</title>
</head>
<body>
    <h1> List of friend</h1>

    <p> My friends are : </p>
    <ul>
        <?php $query = "SELECT * FROM friend";
        $statement = $pdo->prepare($query);
        $statement->execute();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        ?>

        <li> <?= $row['firstname'] . ' ' . $row['lastname'];   ?> </li>
    </ul>
    <?php
    }
    ?>

    <h1> Add your friend in my list</h1>

    <p> You can add you friend in my list by filing the form below</p>

    <form method="post">

        <label for="firstname">Firstname</label>
        <input type="text" id="firstname" name="firstname" value="<?= $firstname ?>">

        <label for="lastname">Lastname</label>
        <input type="text" id="lastname" name="lastname" value="<?= $lastname ?>">

        <button type="submit"> Submit</button>
    </form>
    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>


