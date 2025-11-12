<?php
require_once('../database/db.php');


// Get Opportunity
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT o.*, t.type_name 
          FROM opportunities o
          LEFT JOIN opportunity_types t ON o.type_id = t.id
          WHERE o.id = $id LIMIT 1";
$result = $conn->query($query);
$op = $result->fetch_assoc();

if (!$op) {
  die("<div class='container py-5 text-center'><h3 class='text-danger'>Opportunity not found.</h3></div>");
}

// Organization details
$org_name = $op['institution'] ?: $op['co_web'] ?: "Unknown Organization";
$org_website = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
$qr_link = $org_website . "/details.php?id=" . $op['id'];
$org_phone = "+254 726 583247";
$image_url = $op['image_url'] ?: 'https://via.placeholder.com/640x480';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Opportunity Flyer</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root {
  --blue: hsl(222, 51%, 16%);
  --aqua-100: hsl(205, 72%, 60%);
}

/* Reset */
* { box-sizing: border-box; margin:0; padding:0; font-family: 'Barlow', sans-serif; }
body { background-color: var(--aqua-100); display:flex; justify-content:center; align-items:center; min-height:100vh; }

.main {
  width: 640px;
  height: 840px;
  background-color: #fff;
  position: relative;
  overflow: hidden;
  border-radius: 20px;
  display: flex;
  flex-direction: column;
}

/* Top Section with Background Image */
.bg-image {
  position: relative;
  height: 65%;
  border-radius: 115px 0 115px 0;
  background: url('<?= htmlspecialchars($image_url) ?>') center/cover no-repeat;
  padding: 2em;
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  background-blend-mode: darken;
  background-color: rgba(0,0,0,0.4);
}

/* Logo */
.logo { display: flex; align-items: center; gap: 0.5em; position:absolute; top:2em; left:2em; }
.logo p { font-weight: 600; font-size: 1.5em; }

/* Strategy Text */
.strategy { max-width: 60%; }
.strategy h1 { font-size: 2.3em; margin-bottom: 0.2em; line-height:1.1; }
.strategy h1 span:last-child { color: var(--aqua-100); font-size: 1.2em; display:block; }
.strategy h3 { font-weight: 300; margin-top:0.2em; font-size: 1em; }

/* Bottom Section */
.bottom { flex: 1; background-color: #fff; padding: 1.5em 2em; display: flex; flex-direction: column; justify-content: space-between; }
.bottom p { font-size: 0.9em; line-height: 1.4; margin-bottom: 1em; }

/* Summary Items */
.summary { display: flex; flex-wrap: wrap; gap: 0.5em; margin-bottom: 1em; }
.item { display: flex; align-items: center; gap:0.5em; background: var(--aqua-100); padding: 0.5em 0.7em; border-radius:10px; color:#fff; font-weight:600; font-size:0.95em; flex:1 1 calc(50% - 0.5em); }
.item svg { width:21px; height:21px; fill:#fff; }

/* Apply Button */
.apply-btn { display:inline-block; background-color: #ffc107; color:#fff; font-weight:600; padding:0.75em 1.5em; border-radius:50px; text-align:center; cursor:default; text-decoration:none; align-self:flex-start; }

/* Contact Info + QR */
.contact-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1em;
}

.contact { text-align: left; color:#555; }
.contact h4 { margin-bottom:0.5em; color:#222; }
.contact p { font-size:0.85em; margin-bottom:0.3em; display:flex; align-items:center; gap:0.5em; }

.qr-code img { width:120px; height:120px; }

/* Responsive tweak */
@media(max-width: 680px){
  .main { width:90%; height:auto; }
  .strategy { max-width:65%; }
  .contact-wrapper { flex-direction: column; align-items: flex-start; gap:1em; }
  .qr-code img { width:100px; height:100px; }
}
</style>
</head>
<body>

<div class="main">

  <div class="bg-image">
    <div class="logo">
      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36">
        <circle r="13" cx="18" cy="18" stroke="#fff" stroke-width="3" fill="none" />
        <circle r="8.5" cx="18" cy="18" fill="#fff" />
      </svg>
      <p><?= htmlspecialchars($org_name) ?></p>
    </div>

    <div class="strategy">
      <h1><span><?= htmlspecialchars($op['title']) ?></span><span><?= htmlspecialchars($op['type_name'] ?? 'Opportunity') ?></span></h1>
      <h3><?= htmlspecialchars($op['country'] ?? '-') ?> | <?= date('Y', strtotime($op['created_at'])) ?></h3>
    </div>
  </div>

  <div class="bottom">
    <p><?= htmlspecialchars(substr($op['description'], 0, 100)) ?>...</p>

    <div class="summary">
      <?php if($op['country']): ?><div class="item"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($op['country']) ?></div><?php endif; ?>
      <?php if($org_name): ?><div class="item"><i class="bi bi-building"></i> <?= htmlspecialchars($org_name) ?></div><?php endif; ?>
      <?php if($op['sponsorship']): ?><div class="item"><i class="bi bi-award-fill"></i> <?= htmlspecialchars($op['sponsorship']) ?></div><?php endif; ?>
      <?php if($op['course']): ?><div class="item"><i class="bi bi-clock"></i> <?= htmlspecialchars($op['course']) ?></div><?php endif; ?>
    </div>

    <span class="apply-btn">Apply Now</span>

    <div class="contact-wrapper">
      <div class="contact">
        <h4>Contact Us</h4>
        <p><i class="bi bi-telephone-fill"></i> <?= htmlspecialchars($org_phone) ?></p>
        <p><i class="bi bi-globe"></i> <a href="<?= htmlspecialchars($org_website) ?>" target="_blank"><?= htmlspecialchars($org_website) ?></a></p>
        <p><i class="bi bi-envelope-fill"></i> info@gcstudyabroad.co.ke</p>
      </div>
      <div class="qr-code">
        <div id="qrcode"></div>
        <p style="text-align:center; font-size:0.8em; color:#555;">Scan for Details</p>

      </div>
    </div>
  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  var qrLink = "<?= $qr_link ?>";

  new QRCode(document.getElementById("qrcode"), {
      text: qrLink,
      width: 100,
      height: 100,
      colorDark : "#000000",
      colorLight : "#ffffff",
      correctLevel : QRCode.CorrectLevel.H
  });
</script>


</body>
</html>
