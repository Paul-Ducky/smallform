<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();
var_dump($_SESSION);
function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

//your products with their price.

if(!isset($_GET['food'])) {
$products = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];
}elseif ($_GET['food'] == 1) {
    $products = [
        ['name' => 'Club Ham', 'price' => 3.20],
        ['name' => 'Club Cheese', 'price' => 3],
        ['name' => 'Club Cheese & Ham', 'price' => 4],
        ['name' => 'Club Chicken', 'price' => 4],
        ['name' => 'Club Salmon', 'price' => 5]
    ];
} elseif ($_GET['food'] == 0) {
    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ];
}


$totalValue = 0;

//this is where my code starts.
// we start with empty values to not have start-up errors

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$email = "";
$address = "";
$street = "";
$streetnumber = "";
$zipcode = "";
$city = "";

$errors = [
    'emailErr' => "",
    'nameErr' => "",
    'streetErr' => "",
    'streetNumberErr' => "",
    'cityErr' => "",
    'zipCodeErr' => "",
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email'])) {
        $errors['emailErr'] = "Please enter an E-mail";
    } elseif(!filter_var(test_input($_POST['email']), FILTER_VALIDATE_EMAIL)){
        $errors['emailErr'] = "please enter a valid E-mail";
    }else {
        $email = filter_var(test_input($_POST['email']), FILTER_VALIDATE_EMAIL);
        $_SESSION['email'] = $email;
    }

    if (empty($_POST['street'])) {
        $errors['streetErr'] = "Please enter a valid street";
    } else {
        $street = test_input($_POST['street']);
        $_SESSION['street'] = $street;
    }

    if (empty($_POST['streetnumber'])) {
        $errors['streetNumberErr'] = "Please enter a valid number";
    } elseif(!is_numeric(test_input($_POST['streetnumber']))) {
        $errors['streetNumberErr'] = "please enter a valid number";
    }else {
        $streetnumber = test_input($_POST['streetnumber']);
        $_SESSION['streetnumber'] = $streetnumber;
    }

    if (empty($_POST['city'])) {
        $errors['cityErr'] = "Please enter a valid City";
    } else {
        $city = test_input($_POST['city']);
        $_SESSION['city'] = $city;
    }

    if (empty($_POST['zipcode'])) {
        $errors['zipCodeErr'] = "Please enter a valid zipcode";
    } elseif(!is_numeric(test_input($_POST['zipcode']))) {
        $errors['zipCodeErr'] = "Please enter a valid zipcode";
    }else{
        $zipcode = test_input($_POST['zipcode']);
        $_SESSION['zipcode'] = $zipcode;
    }
}


require 'form-view.php';