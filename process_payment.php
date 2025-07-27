<?php
require_once 'stripe-php/init.php';

// Set your secret key from Stripe Dashboard
\Stripe\Stripe::setApiKey('your_stripe_secret_key');  // Replace with your secret key

// Retrieve POST data from form
$payment_type = $_POST['payment_type'];
$student_id = $_POST['student_id'];
$amount = $_POST['amount'] * 100;  // Amount in cents
$payment_method = $_POST['payment_method'];

// Create a payment intent
try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd',  // Adjust currency as needed
        'description' => $payment_type . ' for student ID ' . $student_id,
        'payment_method_types' => ['card'],
    ]);

    // Return client secret for the frontend to handle
    echo json_encode(['client_secret' => $paymentIntent->client_secret]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
