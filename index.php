<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();

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
//I put the product lists into an if-statement to enable switching on the webpage.
if (!isset($_GET['food']) && !isset($_POST['food']) || isset($_GET['food']) && $_GET['food'] == 1 || (isset($_POST['food']) && $_POST['food'] == 1)) {
    $products = [
        ['name' => 'Club Ham', 'price' => 3.20],
        ['name' => 'Club Cheese', 'price' => 3],
        ['name' => 'Club Cheese & Ham', 'price' => 4],
        ['name' => 'Club Chicken', 'price' => 4],
        ['name' => 'Club Salmon', 'price' => 5]
    ];
    $whatFood = 1;
} elseif ((isset($_POST['food']) && $_POST['food'] == 0) || $_GET['food'] == 0) {
    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ];
    $whatFood = 0;
}

//this is where my code starts.

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// we start with empty values to not have start-up errors
$userInfo = [
    'email' => "",
    'address' => "",
    'street' => "",
    'streetnumber' => "",
    'zipcode' => "",
    'city' => ""
];
$errors = [
    'emailErr' => "",
    'nameErr' => "",
    'streetErr' => "",
    'streetNumberErr' => "",
    'cityErr' => "",
    'zipCodeErr' => "",
];

//check if the cookie totalRev exists
// if not make one starting at 0
// if it does, adjust the new total!
if (isset($_COOKIE['totalRev'])) {
    $totalValue = (float)$_COOKIE['totalRev'];
} else {
    $totalValue = 0;
    setcookie("totalRev", (string)$totalValue, time() + 3600, "/", "", false);
}
// retrieve the user input and validate them to then store for the session,
// also includes the order and error handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email'])) {
        $errors['emailErr'] = "* This is a required field!";
    } elseif (!filter_var(test_input($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $errors['emailErr'] = "* please enter a valid E-mail";
    } else {
        $userInfo['email'] = filter_var(test_input($_POST['email']), FILTER_VALIDATE_EMAIL);
        $_SESSION['email'] = $userInfo['email'];
        $errors['emailErr'] = "";
    }
    if (empty($_POST['street'])) {
        $errors['streetErr'] = "* This is a required field!";
    } else {
        $userInfo['street'] = test_input($_POST['street']);
        $_SESSION['street'] = $userInfo['street'];
        $errors['streetErr'] = "";
    }
    if (empty($_POST['streetnumber'])) {
        $errors['streetNumberErr'] = "* This is a required field!";
    } elseif (!is_numeric(test_input($_POST['streetnumber']))) {
        $errors['streetNumberErr'] = "* please enter a valid number";
    } else {
        $userInfo['streetnumber'] = test_input($_POST['streetnumber']);
        $_SESSION['streetnumber'] = $userInfo['streetnumber'];
        $errors['streetNumberErr'] = "";
    }
    if (empty($_POST['city'])) {
        $errors['cityErr'] = "* This is a required field!";
    } else {
        $userInfo['city'] = test_input($_POST['city']);
        $_SESSION['city'] = $userInfo['city'];
        $errors['cityErr'] = "";
    }
    if (empty($_POST['zipcode'])) {
        $errors['zipCodeErr'] = "* This is a required field!";
    } elseif (!is_numeric(test_input($_POST['zipcode']))) {
        $errors['zipCodeErr'] = "* Please enter a valid zipcode";
    } else {
        $userInfo['zipcode'] = test_input($_POST['zipcode']);
        $_SESSION['zipcode'] = $userInfo['zipcode'];
        $errors['zipCodeErr'] = "";
    }
    // error handling
    $i = 0;
    foreach ($errors as $error) {
        if ($error !== "") {
            $i .= 1;
        }
    }
    if ($i === 0) {
        echo "<div style='background-color: aquamarine; color: darkgreen'><p class='text-center'>Your Order has been placed!</p></div>";
    } elseif ($i > 0) {
        echo "<div style='background-color: indianred; color: whitesmoke'><p class='text-center'>Please check your information!</p></div>";
    }
    // check for express delivery
    if(!empty($_POST['express_delivery'])){
        $totalValue += (float)$_POST['express_delivery'];

    }
    // add up the order total
    for ($i = 0; $i <= count($products); $i++) {
        if (!empty($_POST['products'][$i])) {
            $totalValue += (float)$products[$i]['price'];
        }
    }
}

// new cookie with new totalRev
setcookie("totalRev", (string)$totalValue, time() + 3600, "/", "", false);


whatIsHappening();
require 'form-view.php';