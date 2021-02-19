<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
use JetBrains\PhpStorm\Pure;

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
// todo class productlist  -> class product -> {name , price}
//some initialisations
const DAY_IN_MS = 3600;
const EXPRESS_D_TIME = "+45 minutes";
const NORMAL_D_TIME = "+2 hours";
const ERROR_MSG_1 = "* This is a required field!";
const ERROR_MSG_2 = "* please enter a valid ";
const OWNER_MAIL = "example@example.eg"; // insert mail of the owner here
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
$orderDetails = "";
$orderPrice = 0;

#[Pure] function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function check_mail(){
    if (empty($_POST['email'])) {
        $errors['emailErr'] = ERROR_MSG_1;
    } elseif (!filter_var(test_input($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $errors['emailErr'] = ERROR_MSG_2."E-mail";
    } else {
        $userInfo['email'] = filter_var(test_input($_POST['email']), FILTER_VALIDATE_EMAIL);
        $_SESSION['email'] = $userInfo['email'];
        $errors['emailErr'] = "";
    }
}
function check_street(){
    if (empty($_POST['street'])) {
        $errors['streetErr'] = ERROR_MSG_1;
    } else {
        $userInfo['street'] = test_input($_POST['street']);
        $_SESSION['street'] = $userInfo['street'];
        $errors['streetErr'] = "";
    }
}
function check_streetnumber(){
    if (empty($_POST['streetnumber'])) {
        $errors['streetNumberErr'] = ERROR_MSG_1;
    } elseif (!is_numeric(test_input($_POST['streetnumber']))) {
        $errors['streetNumberErr'] = ERROR_MSG_2."number";
    } else {
        $userInfo['streetnumber'] = test_input($_POST['streetnumber']);
        $_SESSION['streetnumber'] = $userInfo['streetnumber'];
        $errors['streetNumberErr'] = "";
    }
}
function check_city(){
    if (empty($_POST['city'])) {
        $errors['cityErr'] = ERROR_MSG_1;
    } else{
        $userInfo['city'] = test_input($_POST['city']);
        $_SESSION['city'] = $userInfo['city'];
        $errors['cityErr'] = "";
    }
}
function check_zipcode(){
    if (empty($_POST['zipcode'])) {
        $errors['zipCodeErr'] = ERROR_MSG_1;
    } elseif (!is_numeric(test_input($_POST['zipcode']))) {
        $errors['zipCodeErr'] = ERROR_MSG_2."zipcode";
    } else {
        $userInfo['zipcode'] = test_input($_POST['zipcode']);
        $_SESSION['zipcode'] = $userInfo['zipcode'];
        $errors['zipCodeErr'] = "";
    }
}
function check_all_input(){
    check_mail();
    check_street();
    check_streetnumber();
    check_city();
    check_zipcode();
}

//check if the cookie totalRev exists
// if not make one starting at 0
// if it does, adjust the new total!
if (!isset($_COOKIE['totalRev'])) {
    $totalValue = 0;
    setcookie("totalRev", (string)$totalValue, time() + DAY_IN_MS, "/", "", false);
} else {
    $totalValue = (float)$_COOKIE['totalRev'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_all_input();
    // check for express delivery
    if(!empty($_POST['express_delivery'])){
        $totalValue += (float)$_POST['express_delivery'];
        $express = "";
    }
    // add up the order total
    for ($i = 0; $i <= count($products); $i++) {
        if (!empty($_POST['products'][$i])) {
            $totalValue += (float)$products[$i]['price'];
            $orderDetails .=  $products[$i]['name'].", ";
            $orderPrice += (float)$products[$i]['price'];
        }
    }
    //figure out the delivery time
    if(isset($express)){
        $deliveryTime = date("H:i",strtotime(EXPRESS_D_TIME));
    }else{
        $deliveryTime = date("H:i",strtotime(NORMAL_D_TIME));
    }
    // error handling
    $i = 0;
    foreach ($errors as $error) {
        if ($error !== "") {
            $i += 1;
        }
    }
    if ($i === 0) {
        echo "<div style='background-color: aquamarine; color: darkgreen'><p class='text-center'>Your Order has been placed! And will arrive at: $deliveryTime!</p></div>";
    } elseif ($i > 0) {
        echo "<div style='background-color: indianred; color: whitesmoke'><p class='text-center'>Please check your information!</p></div>";
    }
}
// the mail part
/*if (isset($_POST['products'])){
    $subject = "Your order at the Personal Ham Processors!";
    $message = "Dear Sir or Madam,<br>";
    $message .= "We Thank you for your order!<br><br>";
    $message .= "$orderDetails <br> $orderPrice";
    $message .= "Regards,<br>";
    $message .= "the Personal Ham Processors";
    mail($userInfo['email'],$subject,$message);
    mail(OWNER_MAIL,$subject,$message);
}*/
// new cookie with new totalRev
setcookie("totalRev", (string)$totalValue, time() + 3600, "/", "", false);
require 'form-view.php';