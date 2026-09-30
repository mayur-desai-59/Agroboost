<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: main.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - AgroBoost</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #28a745;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .content {
            padding: 20px;
        }
        h1 {
            color: #28a745;
        }
 .cards-container {
    display: flex;
    flex-wrap: wrap; /* Allow wrapping to make sure the cards fit well */
    justify-content: space-between;
    gap: 10px; /* Reduced gap */
    margin-top: 75px;
}

.card {
    background-color: #28a745;
    color: white;
    width: 15%; /* Slightly adjusted width to fit better */
    padding: 15px; /* Keeping the smaller padding */
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    margin-bottom: 10px; /* Added margin at the bottom to separate the rows */
	margin-left: 40px; /* Left margin for spacing */
    margin-right: 40px; /* Right margin for spacing */
}

.card:hover {
    transform: translateY(-5px);
}

.card i {
    font-size: 2.5rem; /* Keeping the smaller icon size */
    margin-bottom: 10px;
}

.card h3 {
    font-size: 1.3rem; /* Keeping the smaller title size */
    margin-bottom: 8px;
}

.card p {
    font-size: 0.9rem; /* Keeping the smaller description size */
    margin-bottom: 15px;
}

.card-link {
    text-decoration: none;
    color: white;
    background-color: #218838;
    padding: 8px 16px; /* Keeping the smaller padding */
    border-radius: 5px;
    font-weight: bold;
}


.card-link:hover {
    background-color: #1e7e34;
}

.agri-fact-box {
    margin-top: 40px;
    padding: 20px;
    background-color: #e8f5e9;
    border-left: 5px solid #28a745;
    border-radius: 10px;
    font-size: 18px;
    color: #2e7d32;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    font-weight: 500;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

		
    </style>
</head>
<body>

<div class="navbar">
    <div style="display: flex; flex-direction: column;">
        <p id="date" style="margin: 0; font-size: 14px;"></p>
        <p id="time" style="margin: 0; font-size: 14px;"></p>
    </div>
    <div style="text-align: center; flex: 1;">
        <strong>AgroBoost - Customer Panel</strong>
    </div>
    <div style="text-align: right;">
		<a href="view_profile.php"><i class="fas fa-user"></i> My Profile</a>
       
    </div>
</div>

<div class="content">
<h1 align=center>Welcome <?php echo htmlspecialchars($_SESSION['name'] ?? 'Customer'); ?>!</h1>
	<div style="text-align: center; margin-top: 20px; font-size: 20px; font-weight: bold; color: #28a745;">
        Did you know?
    </div>

<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;">

<div style="background-color: #e6f4ea; padding: 10px 20px; border-radius: 10px; display: inline-block; box-shadow: 0 0 5px rgba(0,0,0,0.1); font-weight: 500; color: #3b7737; class="agri-fact-box">
    🌾 <span id="agri-fact"></span>
</div>
</div>
<div class="cards-container">
    <!-- View Our Products Card -->
    <div class="card" id="view-products">
        <i class="fas fa-shopping-cart"></i>
        <h3>View Our Products</h3>
        <p>Explore the range of agricultural products we offer.</p>
        <a href="view_products_customers.php" class="card-link">View Products</a>
    </div>

    <!-- Ways to Use Card -->
    <div class="card" id="ways-to-use">
        <i class="fas fa-lightbulb"></i>
        <h3>Ways to Use</h3>
        <p>Learn how to use our products effectively.</p>
        <a href="ways_to_use.html" class="card-link">Learn More</a>
    </div>

    <!-- View Your Purchases Card -->
    <div class="card" id="view-purchases">
        <i class="fas fa-box"></i>
        <h3>View Your Purchases</h3>
        <p>Track and manage your past purchases with ease.</p>
        <a href="my_orders.php" class="card-link">View Purchases</a>
    </div>
	<div class="card">
    <i class="fas fa-shopping-cart"></i>
    <h3>View Cart</h3>
    <p>Check and manage the products you've added to your cart before purchase.</p>
    <a href="view_cart.php" class="card-link">Go to Cart</a>
</div>


</div>

    
</div>
<script>
  function updateDateTime() {
    const now = new Date();

    const dateOptions = {
      weekday: 'long',
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    };
    const dateString = now.toLocaleDateString('en-IN', dateOptions);

    const timeOptions = {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: true
    };
    const timeString = now.toLocaleTimeString('en-IN', timeOptions);

    document.getElementById('date').innerText = `📅 ${dateString}`;
    document.getElementById('time').innerText = `🕒 ${timeString}`;
  }

  setInterval(updateDateTime, 1000);
  updateDateTime();
</script>
<script>
    const facts = [
        "India is the second-largest producer of fruits and vegetables in the world.",
        "Agriculture employs more than 50% of India's workforce.",
        "Drip irrigation can save up to 60% water and increase crop yield.",
        "Soil testing improves crop productivity and reduces cost.",
        "Crop rotation helps in maintaining soil fertility.",
        "Organic farming reduces environmental impact and improves soil health.",
        "India is the largest producer of milk in the world.",
        "Neem-based fertilizers help naturally control pests.",
        "The Green Revolution began in India in the 1960s.",
        "Solar-powered water pumps are becoming popular among Indian farmers.",
		 "Agri-tech innovations like smart sensors help optimize water and nutrient usage.",
        "Beekeeping is a sustainable way to increase agricultural productivity by aiding pollination.",
        "Mulching helps in moisture conservation, temperature regulation, and weed control.",
        "Aquaponics combines fish farming and hydroponics for efficient food production.",
        "Using organic pesticides can help avoid chemical residues on crops and in the environment.",
        "Using natural predators to control pests can reduce the need for harmful chemical pesticides.",
        "Greenhouses provide controlled environments to grow crops, increasing yield and quality.",
        "Incorporating agrochemicals responsibly can increase crop yields without harming the environment.",
		"Farmers can use greenhouses to extend the growing season and protect crops from extreme weather.",
        "Polyculture farming helps maintain biodiversity and soil health.",
        "Intercropping can help improve soil nutrient balance and reduce pest problems.",
        "Hydroponics allows crops to be grown without soil, conserving water and space.",
        "Agri-tech startups are revolutionizing farming by offering innovative solutions for crop monitoring and management.",
        "Biochar is a soil amendment that improves soil fertility and helps in carbon sequestration.",
        "Aquaponics combines aquaculture and hydroponics to create a sustainable farming system.",
        "Using native plant species in farming helps in preserving local ecosystems."
    ];

    // Pick a random fact
    const randomFact = facts[Math.floor(Math.random() * facts.length)];
    document.getElementById("agri-fact").textContent = randomFact;
</script>

</body>
</html>
