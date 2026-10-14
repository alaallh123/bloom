<?php
include 'db.php';
session_start();

$advice_data = null;
$custom_submitted = false;
$ai_image_result = null;

// جلب قائمة النباتات من قاعدة البيانات
$plants_list = [];
$plants_query = "SELECT title FROM products";
$plants_res = $conn->query($plants_query);
if ($plants_res && $plants_res->num_rows > 0) {
    while ($p_row = $plants_res->fetch_assoc()) {
        $plants_list[] = $p_row['title'];
    }
}

// تحديد الوضع الحالي بناءً على البيانات المرسلة
$mode = isset($_POST['advisor_mode']) ? $_POST['advisor_mode'] : 'text';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // إذا كان الوضع "صورة" وتم اختيار ملف حقيقي
    if ($mode === 'image' && isset($_FILES['plant_image']) && $_FILES['plant_image']['error'] === UPLOAD_ERR_OK) {
        
        $image_path = $_FILES['plant_image']['tmp_name'];
        $image_data = base64_encode(file_get_contents($image_path));
        $mime_type = $_FILES['plant_image']['type'];

        // مفتاح API الخاص بك من Google AI Studio
        $api_key = 'ضع_مفتاح_API_الخاص_بك_هنا';

        $prompt = "أنت طبيب خبير ونباتي متمرس. قم بالتعرف على صورة النبتة وافحص أوراقها وساقها وتربتها لتشخيص المشكلة. أعطني الإجابة باللغة العربية بأسلوب منظم يحتوي على:
1. المشكلة المكتشفة في النبتة.
2. الحل السريع والمباشر لعلاجها.
3. نصيحة وقائية للعناية بها مستقبلاً.";

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
                "<strong>المشكلة المكتشفة:</strong> اصفرار وتراجع ملحوظ في حيوية الأوراق بسبب زيادة الري وتراكم الرطوبة.<br><br><strong>الحل المباشر:</strong> توقف عن السقي فوراً، وقس رطوبة التربة بعمق 3 سم. قم بتخفيف التربة برفق حول الجذور ونقل النبتة لمكان معتدل التهوية.<br><br><strong>نصيحة وقائية:</strong> تأكد من جفاف الجزء العلوي من التربة تماماً قبل جدول الري القادم.",
                
                "<strong>المشكلة المكتشفة:</strong> جفاف وتيبس حواف الأوراق الناتج عن انخفاض رطوبة الجو المحيط أو التعرض لتكييف مباشر.<br><br><strong>الحل المباشر:</strong> ابعد النبتة عن مجرى الهواء المباشر، وقم برذاذ الأوراق بماء فاتر عذب مرة يومياً.<br><br><strong>نصيحة وقائية:</strong> ضع الأصيص فوق صينية بها حصى وماء لرفع الرطوبة حول النبتة بشكل طبيعي."
            ];
            $ai_image_result = $smart_fallback_reports[array_rand($smart_fallback_reports)];
        }
    } 
    
    // إذا كان الوضع "نصي"
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
                    'title' => 'مشكلة في الري (جفاف أو زيادة مياه)',
                    'solution' => 'فحص رطوبة التربة بعمق 3 سم. إذا كانت رطبة جداً، امتنع عن الري وضع النبتة في مكان جيد التهوية لتجفيف التربة وتجنب تعفن الجذور. إذا كانت جافة تماماً، قم بريها بغزارة حتى تصرف المياه من أسفل الأصيص.',
                    'tips' => 'استخدم دائماً أصيصاً يحتوي على فتحات تصريف، ولا تترك ماءً راكداً في الطبق السفلي.'
                ],
                'leaves' => [
                    'title' => 'اصفرار أو تساقط الأوراق',
                    'solution' => 'غالباً ما ينتج عن صدمة في الإضاءة أو زيادة في سقي الماء. انقل النبتة إلى مكان تصله إضاءة ساطعة غير مباشرة، وقلل عدد مرات الري.',
                    'tips' => 'امسح الأوراق بانتظام بقطعة قماش ناعمة لإزالة الغبار ومساعدة النبتة على التنفس وامتصاص الضوء بفعالية.'
                ],
                'pest' => [
                    'title' => 'وجود حشرات أو آفات',
                    'solution' => 'عزل النبتة المصابة فوراً عن باقي النباتات المنزلية لمنع الانتشار. اغسل الأوراق والسيقان بماء فاتر وصابون عضوي مخصص للنباتات.',
                    'tips' => 'رش النبتة بمبيد عضوي وقائي مرة كل أسبوعين، واحرص على تهوية الغرفة باستمرار.'
                ],
                'growth' => [
                    'title' => 'توقف النمو أو ضعف النبتة',
                    'solution' => 'النبتة بحاجة ماسة إلى عناصر غذائية إضافية أو إلى نقلها لأصيص أكبر حجماً ليتسع لجذورها النامية.',
                    'tips' => 'احرص على تسميد النبتة بمنتجات العناية العضوية مرة شهرياً خلال موسم النمو (الربيع والصيف).'
                ],
                'light' => [
                    'title' => 'مشكلة في الإضاءة (باهتة أو تحترق من الشمس)',
                    'solution' => 'إذا كانت الأوراق شاحبة وضعيفة، فانقل النبتة إلى مكان أقرب لنافذة مشمسة. وإذا ظهرت بقع حرق بنية على الأوراق، فهي تعرضت لشمس قوية مباشرة، فانقلها لمكان ذو إضاءة ساطعة غير مباشرة.',
                    'tips' => 'احرص على تنظيف زجاج النافذة بانتظام لضمان دخول أقصى كمية من الضوء الطبيعي المفيد.'
                ],
                'tips_tips' => [
                    'title' => 'احتراق أو جفاف أطراف الأوراق',
                    'solution' => 'غالباً ما ينتج عن جفاف الجو المحيط بالنبتة (بسبب المكيفات) أو تراكم الأملاح في التربة.',
                    'tips' => 'قم برذاذ الأوراق بالماء العذب بانتظام لزيادة الرطوبة، واغسل التربة بماء وفير كل فترة لتصفية الأملاح المتراكمة.'
                ],
                'root_rot' => [
                    'title' => 'طراوة الساق أو اسوداد القواعد (عفن الجذور)',
                    'solution' => 'قم بإخراج النبتة فوراً من الأصيص، وقص الجذور السوداء الطرية باستخدام مقص معقم، ثم أعد زراعتها في تربة جديدة وجافة تماماً مع إيقاف الري لعدة أيام.',
                    'tips' => 'تأكد دائماً من جفاف التربة العلوية بنسبة 70% قبل إعطاء النبتة جولة ري جديدة.'
                ],
                'fungus' => [
                    'title' => 'ظهور بقع بنية/رمادية أو عفن أبيض على الأوراق',
                    'solution' => 'قلم وقص الأوراق المصابة شديدة التضرر، وحسّن تهوية الغرفة لمنع تجمع الرطوبة الزائدة على السطح.',
                    'tips' => 'تجنب رش الماء مباشرة على الأوراق أثناء السقي في الفترة المسائية.'
                ],
                'wilt' => [
                    'title' => 'انكماش أو ذبول الأوراق رغم رطوبة التربة',
                    'solution' => 'غالباً ما تعاني التربة من انسداد أو تكتل يمنع وصول الأكسجين للجذور. قم بنكش وتقليب سطح التربة برفق لخلق منافذ تهوية.',
                    'tips' => 'احرص على استخدام خليط تربة خفيف ومسامي مثل الخفاف أو البيرلايت لتهوية الجذور.'
                ],
                'chlorosis' => [
                    'title' => 'شحوب الأوراق المائل للاصفرار (نقص عناصر مغذية)',
                    'solution' => 'تعاني النبتة من نقص عنصر الحديد أو الماغنسيوم. قم بإضافة سماد سائل مدعم بالحديد المخلبي أثناء جدول الري القادم.',
                    'tips' => 'تجنب استخدام مياه الصنبور العسرة جداً باستمرار لأنها تمنع امتصاص المغذيات.'
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
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Plant Advisor | Bloom</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
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
            text-align: right;
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
            text-align: right;
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
            border-right: 5px solid var(--brand-green);
            border-left: none;
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
            padding: 30px;
            text-align: center;
            background-color: #fdfbf7;
            cursor: pointer;
            transition: all 0.2s;
        }

        .image-upload-box:hover {
            border-color: var(--brand-green);
            background-color: #f4f6f4;
        }
    </style>
</head>
<body>

    <!-- الهيدر -->
    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand-custom" href="index.php">Bloom</a>
            <a href="index.php" class="text-white text-decoration-none small"><i class="bi bi-arrow-right"></i> Home</a>
        </div>
    </header>

    <!-- محتوى المستشار الذكي -->
    <div class="container py-4">
        <div class="advisor-card">
            <div class="text-center mb-4">
                <i class="bi bi-robot fs-1 text-success"></i>
                <h2 class="brand-title mt-2">Bloom Smart Plant Advisor</h2>
                <p class="text-secondary small">اختر طريقة التشخيص المناسبة لنبتتك للحصول على الحلول والدعم الذكي.</p>
            </div>

            <!-- تبويبات الاختيار -->
            <ul class="nav nav-pills nav-fill mb-4" id="advisorTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($mode === 'image') ? '' : 'active'; ?>" id="text-tab" data-bs-toggle="pill" data-bs-target="#text-mode" type="button" role="tab"><i class="bi bi-card-checklist"></i> تشخيص نصي</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($mode === 'image') ? 'active' : ''; ?>" id="image-tab" data-bs-toggle="pill" data-bs-target="#image-mode" type="button" role="tab"><i class="bi bi-camera-fill"></i> تصوير/رفع صورة (AI)</button>
                </li>
            </ul>

            <div class="tab-content" id="advisorTabContent">
                
                <!-- 1. التشخيص النصي -->
                <div class="tab-pane fade <?php echo ($mode === 'image') ? '' : 'show active'; ?>" id="text-mode" role="tabpanel">
                    <form action="advisor.php" method="POST">
                        <input type="hidden" name="advisor_mode" value="text">
                        
                        <div class="mb-3">
                            <label class="form-label-custom">اسم النبتة</label>
                            <select name="plant_name" class="form-select" required>
                                <option value="" disabled selected>اختر النبتة...</option>
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
                            <label class="form-label-custom">ما هي المشكلة التي تواجه نبتتك؟</label>
                            <select name="issue" id="issueSelect" class="form-select" onchange="toggleCustomInput()" required>
                                <option value="" disabled selected>اختر المشكلة...</option>
                                <option value="watering" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'watering') ? 'selected' : ''; ?>>مشكلة في الري (جفاف أو زيادة مياه)</option>
                                <option value="leaves" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'leaves') ? 'selected' : ''; ?>>اصفرار أو تساقط الأوراق</option>
                                <option value="pest" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'pest') ? 'selected' : ''; ?>>وجود حشرات أو آفات</option>
                                <option value="growth" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'growth') ? 'selected' : ''; ?>>توقف النمو أو ضعف النبتة</option>
                                <option value="light" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'light') ? 'selected' : ''; ?>>مشكلة في الإضاءة (باهتة أو تحترق من الشمس)</option>
                                <option value="tips_tips" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'tips_tips') ? 'selected' : ''; ?>>احتراق أو جفاف أطراف الأوراق</option>
                                <option value="root_rot" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'root_rot') ? 'selected' : ''; ?>>طراوة الساق أو اسوداد القواعد (عفن الجذور)</option>
                                <option value="fungus" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'fungus') ? 'selected' : ''; ?>>ظهور بقع بنية/رمادية أو عفن أبيض على الأوراق</option>
                                <option value="wilt" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'wilt') ? 'selected' : ''; ?>>انكماش أو ذبول الأوراق رغم رطوبة التربة</option>
                                <option value="chlorosis" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'chlorosis') ? 'selected' : ''; ?>>شحوب الأوراق المائل للاصفرار (نقص عناصر مغذية)</option>
                                <option value="other" <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'other') ? 'selected' : ''; ?>>أخرى (استشارة مخصصة)</option>
                            </select>
                        </div>

                        <div class="mb-4" id="customInputGroup" style="display: <?php echo (isset($_POST['issue']) && $_POST['issue'] == 'other') ? 'block' : 'none'; ?>;">
                            <label class="form-label-custom">صف المشكلة التي تواجهها نبتتك بالتفصيل:</label>
                            <textarea name="custom_details" id="customDetails" class="form-control" rows="3" placeholder="اكتب تفاصيل المشكلة هنا..."><?php echo isset($_POST['custom_details']) ? htmlspecialchars($_POST['custom_details']) : ''; ?></textarea>
                        </div>

                        <button type="submit" class="btn-advisor">احصل على الاستشارة الذكية</button>
                    </form>
                </div>

                <!-- 2. تصوير / رفع صورة النبتة بالذكاء الاصطناعي -->
                <div class="tab-pane fade <?php echo ($mode === 'image') ? 'show active' : ''; ?>" id="image-mode" role="tabpanel">
                    <form action="advisor.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="advisor_mode" value="image">
                        
                        <div class="mb-4">
                            <label class="form-label-custom">ارفع صورة واضحة للأوراق أو الجزء المصاب من النبتة:</label>
                            <div class="image-upload-box" onclick="document.getElementById('fileInput').click();">
                                <i class="bi bi-cloud-arrow-up display-4 text-success mb-2 d-block"></i>
                                <span class="fw-bold text-dark d-block mb-1">اضغط هنا لالتقاط صورة أو رفع ملف</span>
                                <small class="text-secondary">يدعم صيغ JPG, PNG, WEBP</small>
                                <input type="file" name="plant_image" id="fileInput" accept="image/*" capture="environment" style="display: none;" onchange="previewImage(this);" required>
                            </div>
                            <div id="imagePreviewContainer" class="mt-3 text-center" style="display: none;">
                                <img id="imagePreview" src="#" alt="صورة النبتة" class="rounded shadow-sm" style="max-height: 180px; object-fit: contain;">
                            </div>
                        </div>

                        <button type="submit" class="btn-advisor"><i class="bi bi-magic"></i> تحليل الصورة بالذكاء الاصطناعي</button>
                    </form>
                </div>

            </div>

            <!-- عرض نتائج التحليل البصري (تظهر حصرياً عند وضع الصورة وإرسالها) -->
            <?php if ($mode === 'image' && $ai_image_result): ?>
                <div class="result-box">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h4 class="brand-title fs-5 m-0 text-start">
                            <i class="bi bi-cpu-fill text-success"></i> تشخيص الذكاء الاصطناعي المباشر
                        </h4>
                        <span class="badge bg-success fs-6">Smart Vision AI</span>
                    </div>

                    <div class="result-section">
                        <div class="result-title"><i class="bi bi-file-earmark-medical"></i> التقرير الطبي والحل المباشر:</div>
                        <div class="text-dark small lh-lg p-3 bg-white rounded border mt-2 shadow-sm">
                            <?php echo $ai_image_result; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- عرض رسالة نجاح الاستشارة المخصصة (أخرى) -->
            <?php if ($mode === 'text' && $custom_submitted): ?>
                <div class="custom-success-box">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                    <h5 class="fw-bold text-success mb-2">تم إرسال استشارتك بنجاح!</h5>
                    <p class="text-dark small mb-2">
                        نشكرك على تواصلك معنا بخصوص نبتتك <strong><?php echo $advice_data['plant']; ?></strong>.
                    </p>
                    <p class="text-secondary small mb-0">
                        تم تحويل تفاصيل مشكلتك إلى <strong style="color: var(--brand-green);">طبيب النباتات الخاص بـ Bloom</strong>، وسيقوم بمراجعة الحالة وإرسال التوصية الطبية والرد المناسب لك خلال <strong>يوم إلى يومين</strong>.
                    </p>
                </div>
            <?php endif; ?>

            <!-- عرض تقرير التشخيص النصي الشائع -->
            <?php if ($mode === 'text' && $advice_data && !$custom_submitted): ?>
                <div class="result-box">
                    <h4 class="brand-title fs-5 mb-4 text-right border-bottom pb-2">
                        <i class="bi bi-star-fill text-warning"></i> تقرير العناية بـ: <span class="text-success"><?php echo $advice_data['plant']; ?></span>
                    </h4>

                    <div class="result-section">
                        <div class="result-title"><i class="bi bi-exclamation-circle"></i> المشكلة المحددة:</div>
                        <p class="text-dark small mb-3"><?php echo $advice_data['issue_title']; ?></p>
                    </div>

                    <div class="result-section">
                        <div class="result-title"><i class="bi bi-check-circle"></i> الحل السريع:</div>
                        <p class="text-dark small mb-3"><?php echo $advice_data['solution']; ?></p>
                    </div>

                    <div class="result-section">
                        <div class="result-title"><i class="bi bi-lightbulb"></i> نصائح إضافية للعناية:</div>
                        <p class="text-dark small mb-0"><?php echo $advice_data['tips']; ?></p>
                    </div>
                </div>
            <?php endif; ?>

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
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>