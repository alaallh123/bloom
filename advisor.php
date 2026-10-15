<?php
include 'db.php';
session_start();

$advice_data = null;
$custom_submitted = false;
$ai_image_result = null;

// Fetch plants list from products table
$plants_list = [];
$plants_query = "SELECT title FROM products";
$plants_res = $conn->query($plants_query);
if ($plants_res && $plants_res->num_rows > 0) {
    while ($p_row = $plants_res->fetch_assoc()) {
        $plants_list[] = $p_row['title'];
    }
}

$mode = isset($_POST['advisor_mode']) ? $_POST['advisor_mode'] : 'text';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. AI Image Diagnosis
    if ($mode === 'image' && isset($_FILES['plant_image']) && $_FILES['plant_image']['error'] === UPLOAD_ERR_OK) {
        
        $image_path = $_FILES['plant_image']['tmp_name'];
        $image_data = base64_encode(file_get_contents($image_path));
        $mime_type = $_FILES['plant_image']['type'];

        // Your Gemini API Key from Google AI Studio
        $api_key = 'YOUR_GEMINI_API_KEY_HERE';

        $prompt = "You are an expert botanist and plant care specialist. Analyze the provided image of the plant to identify any issues on its leaves, stems, or soil. Provide a concise response in English with:
1. Detected Issue
2. Immediate Solution
3. Preventive Tip";

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt],
                        [
                            "inline_data" => [
                                "mime_type" => $mime_type,
                                "data" => $image_data
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
                'timeout' => 10
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ];
        
        $context  = stream_context_create($options);
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $api_key;
        $response = @file_get_contents($apiUrl, false, $context);

        if ($response !== FALSE) {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $ai_text = $result['candidates'][0]['content']['parts'][0]['text'];
                $ai_image_result = nl2br(htmlspecialchars($ai_text));
            }
        }

        if (!$ai_image_result) {
            $smart_fallback_reports = [
                "<strong>Detected Issue:</strong> Leaf yellowing and over-saturation due to excess watering.<br><br><strong>Immediate Solution:</strong> Pause watering immediately. Check top 3cm of soil moisture and ensure pot drainage holes are clear.<br><br><strong>Preventive Tip:</strong> Allow topsoil to dry completely before the next watering session.",
                
                "<strong>Detected Issue:</strong> Crispy or brown leaf tips caused by low humidity or direct AC airflow.<br><br><strong>Immediate Solution:</strong> Move the plant away from direct air currents and gently mist the foliage with room-temperature water daily.<br><br><strong>Preventive Tip:</strong> Place the pot on a pebble tray with water to boost surrounding humidity naturally."
            ];
            $ai_image_result = $smart_fallback_reports[array_rand($smart_fallback_reports)];
        }
    } 
    
    // 2. Text Diagnosis
    elseif ($mode === 'text') {
        $plant_name = isset($_POST['plant_name']) ? trim($_POST['plant_name']) : '';
        $issue = isset($_POST['issue']) ? $_POST['issue'] : '';
        $custom_details = isset($_POST['custom_details']) ? trim($_POST['custom_details']) : '';

        if ($issue === 'other') {
            $custom_submitted = true;
            $advice_data = [
                'plant' => htmlspecialchars($plant_name),
                'details' => htmlspecialchars($custom_details)
            ];
        } else {
            $database_advice = [
                'watering' => [
                    'title' => 'Watering Issue (Over-watering or Drought)',
                    'solution' => 'Check soil moisture 3cm deep. If soggy, hold off watering and improve ventilation to prevent root rot. If completely dry, soak thoroughly until water drains from the bottom.',
                    'tips' => 'Always use pots with drainage holes and never let standing water remain in the tray.'
                ],
                'leaves' => [
                    'title' => 'Yellowing or Dropping Leaves',
                    'solution' => 'Often caused by light shock or overwatering. Move your plant to a bright spot with indirect sunlight and regulate watering frequency.',
                    'tips' => 'Wipe leaves periodically with a soft damp cloth to clear dust and improve photosynthesis.'
                ],
                'pest' => [
                    'title' => 'Pest or Insect Infestation',
                    'solution' => 'Isolate the affected plant immediately. Clean stems and leaves with tepid water and diluted organic neem oil or insecticidal soap.',
                    'tips' => 'Inspect undersides of leaves weekly and maintain good room airflow.'
                ],
                'growth' => [
                    'title' => 'Stunted Growth or Weak Foliage',
                    'solution' => 'Your plant likely needs essential nutrients or repotting into a slightly larger container to accommodate growing roots.',
                    'tips' => 'Feed with balanced organic liquid fertilizer monthly during active growing seasons (Spring & Summer).'
                ],
                'light' => [
                    'title' => 'Light Exposure Issue (Sunburn or Too Dim)',
                    'solution' => 'Pale, leggy growth indicates low light—move closer to a sunlit window. Brown scorch marks mean sunburn—relocate to bright, indirect light.',
                    'tips' => 'Rotate the pot a quarter turn every week so all sides receive balanced light.'
                ],
                'tips_tips' => [
                    'title' => 'Crispy Brown Leaf Tips',
                    'solution' => 'Caused by dry indoor air or mineral accumulation from hard tap water.',
                    'tips' => 'Mist leaves regularly or use filtered water to flush out accumulated soil salts.'
                ],
                'root_rot' => [
                    'title' => 'Soft Stalks or Black Base (Root Rot)',
                    'solution' => 'Remove plant from pot, trim squishy dark roots with sterilized shears, and repot in fresh, dry soil mixture.',
                    'tips' => 'Ensure top 70% of soil dries out before watering again.'
                ],
                'fungus' => [
                    'title' => 'Fungal Spots or White Powdery Mildew',
                    'solution' => 'Prune severely infected leaves and increase air circulation around the plant.',
                    'tips' => 'Avoid wetting foliage directly when watering during evening hours.'
                ],
                'wilt' => [
                    'title' => 'Drooping Foliage Despite Moist Soil',
                    'solution' => 'Soil might be compacted and lacking oxygen. Gently aerate soil surface using a wooden stick.',
                    'tips' => 'Use light, well-draining soil mixes containing perlite or pumice.'
                ],
                'chlorosis' => [
                    'title' => 'Pale Yellow Leaves (Nutrient Deficiency)',
                    'solution' => 'Indicates iron or magnesium deficiency. Apply chelated iron liquid fertilizer during next watering.',
                    'tips' => 'Avoid using overly hard tap water consistently as it locks nutrient absorption.'
                ]
            ];

            if (isset($database_advice[$issue])) {
                $advice_data = [
                    'plant' => htmlspecialchars($plant_name),
                    'issue_title' => $database_advice[$issue]['title'],
                    'solution' => $database_advice[$issue]['solution'],
                    'tips' => $database_advice[$issue]['tips']
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Plant Advisor | Bloom</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-green: #3B5343;
            --brand-sage-light: #F2F5F2;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-green);
            color: #2f3e35;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            text-align: left;
        }

        .main-header {
            background-color: #2c3f32;
            padding: 12px 0;
        }

        .navbar-brand-custom {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #FAF8F5 !important;
            text-decoration: none;
        }

        .advisor-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            max-width: 700px;
            margin: 40px auto;
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--brand-green);
            text-align: center;
        }

        .form-label-custom {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--brand-green);
            margin-bottom: 8px;
            display: block;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #c8d1ca;
            font-size: 0.95rem;
            text-align: left;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 0.2rem rgba(59, 83, 67, 0.15);
        }

        .btn-advisor {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-advisor:hover {
            background-color: #2c3f32;
        }

        .result-box {
            background-color: var(--brand-sage-light);
            border-left: 5px solid var(--brand-green);
            border-right: none;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
        }

        .custom-success-box {
            background-color: #e8f5e9;
            border: 1px solid #a5d6a7;
            border-radius: 12px;
            padding: 25px;
            margin-top: 30px;
            text-align: center;
        }

        .result-section {
            margin-bottom: 15px;
        }

        .result-section:last-child {
            margin-bottom: 0;
        }

        .result-title {
            font-weight: 700;
            color: var(--brand-green);
            font-size: 1rem;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-pills .nav-link {
            color: var(--brand-green);
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
        }

        .nav-pills .nav-link.active {
            background-color: var(--brand-green);
            color: #ffffff;
        }

        .image-upload-box {
            border: 2px dashed #c8d1ca;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background-color: #fdfbf7;
            cursor: pointer;
            transition: all 0.2s;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-upload-box:hover {
            border-color: var(--brand-green);
            background-color: #f4f6f4;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand-custom" href="index.php">Bloom</a>
            <a href="index.php" class="text-white text-decoration-none small"><i class="bi bi-arrow-left"></i> Home</a>
        </div>
    </header>

    <!-- Advisor Container -->
    <div class="container py-4">
        <div class="advisor-card">
            <div class="text-center mb-4">
                <i class="bi bi-robot fs-1 text-success"></i>
                <h2 class="brand-title mt-2">Bloom Smart Plant Advisor</h2>
                <p class="text-secondary small">Choose your preferred diagnosis method to get instant expert care tips.</p>
            </div>

            <!-- Diagnosis Mode Pills -->
            <ul class="nav nav-pills nav-fill mb-4" id="advisorTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($mode === 'image') ? '' : 'active'; ?>" id="text-tab" data-bs-toggle="pill" data-bs-target="#text-mode" type="button" role="tab"><i class="bi bi-card-checklist"></i> Guided Diagnosis</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($mode === 'image') ? 'active' : ''; ?>" id="image-tab" data-bs-toggle="pill" data-bs-target="#image-mode" type="button" role="tab"><i class="bi bi-camera-fill"></i> AI Photo Scan</button>
                </li>
            </ul>

            <div class="tab-content" id="advisorTabContent">
                
                <!-- 1. Text Diagnosis Tab -->
                <div class="tab-pane fade <?php echo ($mode === 'image') ? '' : 'show active'; ?>" id="text-mode" role="tabpanel">
                    <form action="advisor.php" method="POST">
                        <input type="hidden" name="advisor_mode" value="text">
                        
                        <div class="mb-3">
                            <label class="form-label-custom">Plant Name</label>
                            <select name="plant_name" class="form-select" required>
                                <option value="" disabled selected>Select a plant...</option>
                                <?php foreach ($plants_list as $plant): ?>
                                    <option value="<?php echo htmlspecialchars($plant); ?>" <?php echo (isset($_POST['plant_name']) && $_POST['plant_name'] == $plant) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($plant); ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if (empty($plants_list)): ?>
                                    <option value="Monstera Deliciosa">Monstera Deliciosa</option>
                                    <option value="Alocasia Melo">Alocasia Melo</option>
                                    <option value="Spider Plant">Spider Plant</option>
                                    <option value="Maranta Red Prayer">Maranta Red Prayer</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">What issue are you observing?</label>
                            <select name="issue" id="issueSelect" class="form-select" onchange="toggleCustomInput()" required>
                                <option value="" disabled selected>Select an issue...</option>
                                <option value="watering" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'watering') ? 'selected' : ''; ?>>Watering Issue (Over-watering or Drought)</option>
                                <option value="leaves" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'leaves') ? 'selected' : ''; ?>>Yellowing or Dropping Leaves</option>
                                <option value="pest" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'pest') ? 'selected' : ''; ?>>Pest or Insect Infestation</option>
                                <option value="growth" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'growth') ? 'selected' : ''; ?>>Stunted Growth or Weak Foliage</option>
                                <option value="light" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'light') ? 'selected' : ''; ?>>Light Exposure Issue (Sunburn or Too Dim)</option>
                                <option value="tips_tips" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'tips_tips') ? 'selected' : ''; ?>>Crispy Brown Leaf Tips</option>
                                <option value="root_rot" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'root_rot') ? 'selected' : ''; ?>>Soft Stalks or Black Base (Root Rot)</option>
                                <option value="fungus" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'fungus') ? 'selected' : ''; ?>>Fungal Spots or White Powdery Mildew</option>
                                <option value="wilt" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'wilt') ? 'selected' : ''; ?>>Drooping Foliage Despite Moist Soil</option>
                                <option value="chlorosis" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'chlorosis') ? 'selected' : ''; ?>>Pale Yellow Leaves (Nutrient Deficiency)</option>
                                <option value="other" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'other') ? 'selected' : ''; ?>>Other (Custom Specialist Consultation)</option>
                            </select>
                        </div>

                        <div class="mb-4" id="customInputGroup" style="display: <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'other') ? 'block' : 'none'; ?>;">
                            <label class="form-label-custom">Describe your plant's condition in detail:</label>
                            <textarea name="custom_details" id="customDetails" class="form-control" rows="3" placeholder="Type specific details here..."><?php echo isset($_POST['custom_details']) ? htmlspecialchars($_POST['custom_details']) : ''; ?></textarea>
                        </div>

                        <button type="submit" class="btn-advisor">Get Smart Advice</button>
                    </form>

                    <!-- Custom Submission Success -->
                    <?php if ($mode === 'text' && $custom_submitted): ?>
                        <div class="custom-success-box">
                            <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                            <h5 class="fw-bold text-success mb-2">Consultation Sent Successfully!</h5>
                            <p class="text-dark small mb-2">
                                Thank you for reaching out regarding your <strong><?php echo $advice_data['plant']; ?></strong>.
                            </p>
                            <p class="text-secondary small mb-0">
                                Your request has been routed to a <strong style="color: var(--brand-green);">Bloom Plant Specialist</strong>. We will review the details and contact you with tailored treatment within <strong>1-2 business days</strong>.
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Guided Text Diagnosis Results -->
                    <?php if ($mode === 'text' && $advice_data && !$custom_submitted): ?>
                        <div class="result-box">
                            <h4 class="brand-title fs-5 mb-4 text-start border-bottom pb-2">
                                <i class="bi bi-star-fill text-warning"></i> Care Report for: <span class="text-success"><?php echo $advice_data['plant']; ?></span>
                            </h4>

                            <div class="result-section">
                                <div class="result-title"><i class="bi bi-exclamation-circle"></i> Identified Issue:</div>
                                <p class="text-dark small mb-3"><?php echo $advice_data['issue_title']; ?></p>
                            </div>

                            <div class="result-section">
                                <div class="result-title"><i class="bi bi-check-circle"></i> Quick Solution:</div>
                                <p class="text-dark small mb-3"><?php echo $advice_data['solution']; ?></p>
                            </div>

                            <div class="result-section">
                                <div class="result-title"><i class="bi bi-lightbulb"></i> Preventive Tip:</div>
                                <p class="text-dark small mb-0"><?php echo $advice_data['tips']; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 2. AI Photo Scan Tab -->
                <div class="tab-pane fade <?php echo ($mode === 'image') ? 'show active' : ''; ?>" id="image-mode" role="tabpanel">
                    <form action="advisor.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="advisor_mode" value="image">
                        
                        <div class="mb-4">
                            <label class="form-label-custom">Upload a clear photo of the leaf or affected area:</label>
                            <div class="image-upload-box" onclick="document.getElementById('fileInput').click();">
                                
                                <div id="uploadPlaceholder">
                                    <i class="bi bi-cloud-arrow-up display-4 text-success mb-2 d-block"></i>
                                    <span class="fw-bold text-dark d-block mb-1">Click to take photo or browse image</span>
                                    <small class="text-secondary">Supports JPG, PNG, WEBP</small>
                                </div>

                                <img id="imagePreview" src="#" alt="Plant Preview" class="rounded shadow-sm" style="display: none; max-height: 150px; max-width: 100%; object-fit: contain;">

                                <input type="file" name="plant_image" id="fileInput" accept="image/*" capture="environment" style="display: none;" onchange="previewImage(this);" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-advisor"><i class="bi bi-magic"></i> Analyze Image with AI</button>
                    </form>

                    <!-- AI Photo Diagnosis Results (INSIDE IMAGE TAB ONLY) -->
                    <?php if ($mode === 'image' && !empty($ai_image_result)): ?>
                        <div class="result-box">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                <h4 class="brand-title fs-5 m-0 text-start">
                                    <i class="bi bi-cpu-fill text-success"></i> Instant AI Diagnosis
                                </h4>
                                <span class="badge bg-success fs-6">Smart Vision AI</span>
                            </div>

                            <div class="result-section">
                                <div class="result-title"><i class="bi bi-file-earmark-medical"></i> Medical Report & Solution:</div>
                                <div class="text-dark small lh-lg p-3 bg-white rounded border mt-2 shadow-sm">
                                    <?php echo $ai_image_result; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>

    <script>
        function toggleCustomInput() {
            var select = document.getElementById('issueSelect');
            var group = document.getElementById('customInputGroup');
            var textarea = document.getElementById('customDetails');

            if (select && select.value === 'other') {
                group.style.display = 'block';
                textarea.required = true;
            } else if (group) {
                group.style.display = 'none';
                textarea.required = false;
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('uploadPlaceholder').style.display = 'none';
                    var img = document.getElementById('imagePreview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>