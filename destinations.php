<?php  
include 'database/db.php';


// Load country data from local JSON file
$countriesJsonPath = './assets/dataset/countries.json';
$countriesJson = file_exists($countriesJsonPath) ? file_get_contents($countriesJsonPath) : '[]';
$countriesList = json_decode($countriesJson, true) ?? [];

// Create lookup array for fast matching
$flagLookup = [];
foreach ($countriesList as $item) {
    $countryName = strtolower(trim($item['country'] ?? ''));
    if ($countryName === '') continue; // skip empty entries

    $flag = $item['flag'] ?? "https://via.placeholder.com/90x60?text=No+Flag";
    $code = isset($item['code']) && $item['code'] !== '' 
        ? strtolower($item['code']) 
        : strtolower(substr($countryName, 0, 2)); // fallback

    $flagLookup[$countryName] = [
        'flag' => $flag,
        'code' => $code
    ];
}

// Fetch all opportunities (for countries)
$query = "SELECT * FROM opportunities WHERE country IS NOT NULL AND country <> ''";
$result = $conn->query($query);

$countriesData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $countryRaw = $row['country'];
        $countryObj = json_decode($countryRaw, true);

        // Extract country name
        $countryName = $countryObj['country'] ?? $countryRaw;
        $countryKey = strtolower(trim($countryName));

        // Match flag from local JSON
        if (isset($flagLookup[$countryKey])) {
            $flag = $flagLookup[$countryKey]['flag'];
            $countryCode = $flagLookup[$countryKey]['code'];
        } else {
            // fallback if not found
            $countryCode = strtolower(substr($countryName, 0, 2));
            $flag = "https://countryflagsapi.com/png/{$countryCode}";
        }

        // Avoid duplicates
        if (!array_key_exists($countryName, $countriesData)) {
            $countriesData[$countryName] = [
                'country' => $countryName,
                'code' => $countryCode,
                'flag' => $flag
            ];
        }
    }
}

// Fetch latest 5 opportunities
$latestQuery = "SELECT * FROM opportunities ORDER BY created_at DESC LIMIT 5";
$latestResult = $conn->query($latestQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Destinations | Study & Work Abroad</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .country-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      cursor: pointer;
    }
    .country-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    #map {
      height: 450px;
      width: 100%;
      border-radius: 12px;
      margin-top: 30px;
    }
    .flag-img {
      height: 60px;
      width: 90px;
      object-fit: cover;
      border-radius: 6px;
    }
    .opportunity-card img {
      height: 180px;
      object-fit: cover;
      border-radius: 6px;
    }
  </style>


 <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9fbff;
    }
    .hero-header {
      background: linear-gradient(135deg, #072F5F, #FF7A00);
      color: #fff;
      padding: 80px 0;
      text-align: center;
    }
    .hero-header h1 { font-weight: 700; }
    .btn-orange {
      background-color: #FF7A00;
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 10px 20px;
    }
    .btn-orange:hover { background-color: #e66b00; }
    .btn-blue {
      background-color: #072F5F;
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 10px 20px;
    }
    .btn-blue:hover { background-color: #051f3d; }
    .details-section {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      margin-top: -60px;
      position: relative;
      z-index: 2;
    }
    .share-section {
      margin-top: 50px;
      text-align: center;
    }
    .share-buttons a, .share-buttons button {
      margin: 5px;
      border-radius: 25px;
      padding: 10px 18px;
      color: white;
      text-decoration: none;
      display: inline-block;
    }
    .share-facebook { background-color: #1877F2; }
    .share-whatsapp { background-color: #25D366; }
    .share-copy { background-color: #555; border: none; }
    .related-section {
      margin-top: 80px;
    }
    .card {
      border: 1px solid #ddd;
      border-radius: 10px;
      transition: 0.3s;
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>


<!-- Navbar -->
<?php include('./includes/navbar.php'); ?>

<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Explore Study & Work Destinations</h2>
      <p class="text-muted">Click a country to see available scholarships, jobs, or programs.</p>
    </div>

    <!-- Country Cards -->
    <div class="row g-4 justify-content-center">
      <?php if (!empty($countriesData)): ?>
        <?php foreach ($countriesData as $country): ?>
          <div class="col-md-3 col-6">
            <div class="card country-card border-0 shadow-sm text-center" onclick="showOpportunities('<?php echo htmlspecialchars($country['country']); ?>')">
              <div class="card-body">
                <img 
                  src="<?php echo htmlspecialchars($country['flag']); ?>" 
                  alt="<?php echo htmlspecialchars($country['country']); ?> Flag" 
                  class="flag-img mb-3" 
                  onerror="this.src='https://via.placeholder.com/90x60?text=Flag'">
                <h5 class="fw-semibold"><?php echo htmlspecialchars($country['country']); ?></h5>
              </div>
            </div>
          </div>
        <?php endforeach;?>
      <?php else: ?>
        <p class="text-center text-muted">No destinations available yet.</p>
      <?php endif; ?>
    </div>

    <!-- Map -->
    <div id="map"></div>

    <!-- Dynamic Opportunities Section -->
    <div class="mt-5" id="opportunities-section" style="display:none;">
      <h3 class="fw-bold text-primary mb-4" id="selected-country-title"></h3>
      <div id="opportunities-container" class="row g-4"></div>
    </div>

    <!-- Latest 5 Opportunities -->
    <div class="mt-5">
      <h3 class="fw-bold text-primary mb-4 text-center">Latest Opportunities</h3>
      <div class="row g-4">
        <?php if ($latestResult && $latestResult->num_rows > 0): ?>
          <?php while ($op = $latestResult->fetch_assoc()): 
            $countryData = json_decode($op['country'], true);
            $countryName = $countryData['country'] ?? $op['country'];
          ?>
            <div class="col-md-4 col-lg-3 col-12">
              <div class="card border-0 shadow-sm h-100 opportunity-card">
                <img src="uploads/<?php echo htmlspecialchars($op['image_url']);?>" 
                     alt="<?php echo htmlspecialchars($op['title']); ?>" 
                     class="card-img-top">
                <div class="card-body">
                  <h6 class="fw-bold text-truncate"><?php echo htmlspecialchars($op['title']); ?></h6>
                  <p class="text-muted small mb-2"><?php echo htmlspecialchars($op['institution']); ?> — <?php echo htmlspecialchars($countryName); ?></p>
                  <p class="small"><?php echo substr(htmlspecialchars($op['description']), 0, 80); ?>...</p>
                  <a href="<?php echo htmlspecialchars($op['opp_url']); ?>" target="_blank" class="btn btn-sm btn-primary w-100">View Details</a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-center text-muted">No opportunities available yet.</p>
        <?php endif; ?>
      </div>
    </div>

  </div>
</section>




<!-- ======= Footer Section ======= -->
<footer class="pt-5 bg-dark text-light mt-5">
  <div class="container">
    <div class="row gy-4">

      <!-- Contact Info -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-3 text-uppercase text-warning">Contact Us</h5>
        <p><i class="bi bi-geo-alt-fill me-2"></i> Nairobi, Kenya</p>
        <p><i class="bi bi-envelope-fill me-2"></i> info@gamechanger.co.ke</p>
        <p><i class="bi bi-telephone-fill me-2"></i> +254 726 874 170</p>
      </div>

      <!-- Follow Us -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-3 text-uppercase text-warning">Follow Us</h5>
        <p>Stay connected for the latest updates and opportunities.</p>
        <div class="d-flex gap-3 fs-4">
          <a href="#" target="_blank" class="text-light"><i class="bi bi-facebook"></i></a>
          <a href="#" target="_blank" class="text-light"><i class="bi bi-twitter-x"></i></a>
          <a href="#" target="_blank" class="text-light"><i class="bi bi-tiktok"></i></a>
          <a href="#" target="_blank" class="text-light"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

      <!-- Newsletter -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-3 text-uppercase text-warning">Newsletter</h5>
        <p>Subscribe for updates on study and work abroad opportunities.</p>
        <form class="d-flex">
          <input type="email" class="form-control me-2" placeholder="Enter your email" required>
          <button class="btn btn-warning text-dark fw-semibold" type="submit">Go</button>
        </form>
      </div>

      <!-- Partners & Destinations -->
      <div class="col-md-3">
        <h6 class="fw-bold text-uppercase text-warning mt-3">Top Destinations</h6>
        <ul class="list-unstyled mb-0">
          <li>🇨🇦 Canada</li>
          <li>🇬🇧 United Kingdom</li>
          <li>🇺🇸 USA</li>
          <li>🇦🇺 Australia</li>
          <li>🇩🇪 Germany</li>
        </ul>
      </div>

    </div>

    <hr class="my-4 border-light">
    <div class="text-center small">
      <p class="mb-0">&copy; <?= date('Y') ?> GameChanger Consulting. All Rights Reserved.</p>
    </div>
  </div>
</footer>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<style>
footer {
  font-family: 'Poppins', sans-serif;
}
footer h5, footer h6 {
  letter-spacing: 1px;
}
footer ul li {
  color: #ccc;
  margin-bottom: 6px;
}
footer ul li:hover {
  color: #fff;
}
footer form input {
  border-radius: 25px;
  padding-left: 15px;
}
footer form button {
  border-radius: 25px;
}
footer a:hover {
  color: #ff7a00 !important;
}

</style>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<script>
function copyLink() {
  const url = window.location.href;
  navigator.clipboard.writeText(url);
  alert("Link copied to clipboard!");
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const availableCountries = <?php echo json_encode(array_values($countriesData)); ?>;

const map = L.map('map').setView([0, 0], 2);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors',
  maxZoom: 18
}).addTo(map);

async function addMarkers() {
  for (const country of availableCountries) {
    try {
      const response = await fetch(`https://nominatim.openstreetmap.org/search?country=${encodeURIComponent(country.country)}&format=json&limit=1`);
      const data = await response.json();
      if (data && data.length > 0) {
        const { lat, lon } = data[0];
        const flagImg = `<img src="${country.flag}" alt="${country.country} flag" style="width:50px; height:30px; border-radius:4px; object-fit:cover;">`;
        L.marker([lat, lon])
          .addTo(map)
          .bindPopup(`${flagImg}<br><strong>${country.country}</strong>`)
          .on('click', () => showOpportunities(country.country));
      }
    } catch (err) {
      console.error('Error fetching coordinates for', country.country, err);
    }
  }
}
addMarkers();

function showOpportunities(country) {
  document.getElementById('selected-country-title').innerText = `Opportunities in ${country}`;
  document.getElementById('opportunities-section').style.display = 'block';

  fetch(`backend/fetch_opportunities.php?country=${encodeURIComponent(country)}`)
    .then(res => res.text())
    .then(html => {
      document.getElementById('opportunities-container').innerHTML = html;
      window.scrollTo({ top: document.getElementById('opportunities-section').offsetTop - 100, behavior: 'smooth' });
    });
}
</script>

</body>
</html>
