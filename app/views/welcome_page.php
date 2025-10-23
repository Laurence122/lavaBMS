<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .hero-section {
            background: url('https://via.placeholder.com/1500x600') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .section {
            padding: 60px 0;
        }
        .card-deck .card {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <h1 class="display-4">Welcome to Barangay [Barangay Name]</h1>
            <p class="lead">Your one-stop portal for barangay services and information.</p>
        </div>
    </header>

    <!-- About Us Section -->
    <section id="about" class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>About Our Barangay</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl nec ultricies lacinia, nisl nisl aliquet nisl, nec aliquam nisl nisl sit amet nisl. Donec euismod, nisl nec ultricies lacinia, nisl nisl aliquet nisl, nec aliquam nisl nisl sit amet nisl.</p>
                </div>
                <div class="col-md-6">
                    <img src="https://via.placeholder.com/500x300" class="img-fluid" alt="Barangay Hall">
                </div>
            </div>
        </div>
    </section>

    <!-- Barangay Officials Section -->
    <section id="officials" class="section bg-light">
        <div class="container">
            <h2 class="text-center">Barangay Officials</h2>
            <div class="row card-deck">
                <div class="col-md-4">
                    <div class="card text-center">
                        <img src="https://via.placeholder.com/150" class="card-img-top rounded-circle mx-auto mt-3" alt="Official">
                        <div class="card-body">
                            <h5 class="card-title">Juan Dela Cruz</h5>
                            <p class="card-text">Barangay Captain</p>
                        </div>
                    </div>
                </div>
                <!-- Add more officials as needed -->
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section id="map" class="section">
        <div class="container">
            <h2 class="text-center">Our Location</h2>
            <div id="map-container" style="height: 400px;"></div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section bg-light">
        <div class="container">
            <h2 class="text-center">Contact Us</h2>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <form>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your Name">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Your Email">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="5" placeholder="Your Message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
    <script>
        function initMap() {
            var location = {lat: -25.344, lng: 131.036};
            var map = new google.maps.Map(document.getElementById('map-container'), {
                zoom: 4,
                center: location
            });
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }
    </script>

</body>
</html>
