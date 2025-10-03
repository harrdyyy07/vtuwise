<?php
require_once 'common/header.php';

// Variable to hold form submission status
$message_status = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_status = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p>Please fill in all fields correctly.</p></div>';
    } else {
        $to = 'siddub353@gmail.com'; // IMPORTANT: Change this to your actual email address
        $headers = 'From: ' . $email . "\r\n" .
                   'Reply-To: ' . $email . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        $full_message = "You have received a new message from your website contact form.\n\n" .
                        "Here are the details:\n\n" .
                        "Name: $name\n" .
                        "Email: $email\n" .
                        "Subject: $subject\n" .
                        "Message:\n$message\n";

        // The mail() function requires a configured mail server to work.
        if (mail($to, $subject, $full_message, $headers)) {
            $message_status = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>Thank you for your message. It has been sent.</p></div>';
        } else {
            $message_status = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p>Sorry, there was an error sending your message. Please try again later.</p></div>';
        }
    }
}
?>

<div class="container mx-auto py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-slate-800">Get in Touch</h1>
            <p class="text-lg text-slate-500 mt-2">We'd love to hear from you. Please fill out the form below.</p>
        </div>

        <?php echo $message_status; // Display success or error message here ?>

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-6">
                <h2 class="text-2xl font-bold text-slate-700">Contact Information</h2>
                <div class="flex items-start space-x-4">
                    <i class="fas fa-map-marker-alt text-2xl text-blue-500 mt-1"></i>
                    <div>
                        <h3 class="font-semibold">Address</h3>
                        <p class="text-slate-600">VTU, Jnana Sangama, Belagavi, Karnataka 590018</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <i class="fas fa-envelope text-2xl text-blue-500 mt-1"></i>
                    <div>
                        <h3 class="font-semibold">Email</h3>
                        <a href="mailto:contact@vtunotes.example.com" class="text-slate-600 hover:text-blue-600">contact@vtunotes.com</a>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <i class="fas fa-phone text-2xl text-blue-500 mt-1"></i>
                    <div>
                        <h3 class="font-semibold">Phone</h3>
                        <a href="tel:+9108312498100" class="text-slate-600 hover:text-blue-600">+91 (0831) 2498100</a>
                    </div>
                </div>
                <div class="pt-4">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3838.411183111867!2d74.6212543152649!3d15.835472851549416!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbf66a95840458b%3A0x66a6d59a72b22db7!2sVisvesvaraya%20Technological%20University!5e0!3m2!1sen!2sin!4v1672842189012!5m2!1sen!2sin" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-lg"></iframe>
                </div>
            </div>

            <div>
                <form action="contactus.php" method="POST" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" required class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" required class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" name="subject" id="subject" required class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" id="message" rows="5" required class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold text-lg p-3 rounded-lg hover:bg-blue-700">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'common/footer.php'; ?>