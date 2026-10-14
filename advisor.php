<?php
session_start();

$advice_result = "";
$plant_name = "";

// منطق الرد التلقائي من المساعد الذكي بناءً على اختيار المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plant_name = trim($_POST['plant_name']);
    $issue = $_POST['issue'];

    if ($issue === 'yellow_leaves') {
        $advice_result = "صفراء الأوراق غالباً تعني **زيادة في الري** أو نقص في الضوء. تأكد من أن التربة تجف تماماً بين الريات، وضع النبتة في مكان تصله إضاءة ساطعة وغير مباشرة.";
    } elseif ($issue === 'drooping') {
        $advice_result = "الذبول أو التدلي يشير عادة إلى **العطش الشديد** أو جفاف التربة. قم بري النبتة غَمراً حتى تخرج المياه من فتحات التصريف بالأسفل.";
    } elseif ($issue === 'brown_tips') {
        $advice_result = "الأطراف البنية تعني غالباً **جفاف الجو** أو تراكم الأملاح من ماء الصنبور. حاول رش الأوراق برذاذ الماء بانتظام أو استخدام ماء مصفى.";
    } elseif ($issue === 'recommendation') {
        $advice_result = "بناءً على اختيارك لـ **$plant_name**، ننصحك بريها مرة كل أسبوعين في الشتاء وكل أسبوع في الصيف، وتسميدها بمنتجات العناية العضوية مرة شهرياً خلال موسم النمو.";
    } else {
        $advice_result = "نصيحة عامة: حافظ على جدول ري منتظم، وتجنب تعريض النبتة لتيارات الهواء القوية أو المكيفات المباشرة.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Advisor | Bloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --brand-bg: #FAF8F5; 
            --brand-green: #3B5343; 
            --brand-sage-light: #F2F5F2; 
            --brand-divider: #E7E0D6; 
            --brand-text: #2f3e35;
        }
        body { background-color: var(--brand-bg); color: var(--brand-text); font-family: 'Inter', sans-serif; }
        .main-header { background-color: var(--brand-green); padding: 12px 0; }
        .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #FAF8F5 !important; text-decoration: none; }
        .brand-title-font { font-family: 'Playfair Display', serif; font-weight: 700; color: var(--brand-green); }
        .btn-bloom { background-color: var(--brand-green); color: #ffffff; border-radius: 8px; font-weight: 500; padding: 0.8rem 1.5rem; border: none; width: 100%; }
        .btn-bloom:hover { background-color: #2c3f32; color: #ffffff; }
        .advisor-card { background: #ffffff; border-radius: 12px; border: 1px solid var(--brand-divider); padding: 30px; box-shadow: 0 10px 25px rgba(59, 83, 67, 0.05); }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand-custom" href="index.php">Bloom</a>
            <a href="index.php" class="text-white text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to Home</a>
        </div>
    </header>

    <div class="container my-5" style="max-width: 650px;">
        <h2 class="brand-title-font mb-3 text-center"><i class="bi bi-robot text-success"></i> Bloom Smart Plant Advisor</h2>
        <p class="text-secondary text-center mb-4">اختر المشكلة التي تواجه نبتتك أو استشرنا للحصول على التوصيات الصحيحة للعناية بها.</p>

        <div class="advisor-card">
            <form action="advisor.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">اسم النبتة (اختياري)</label>
                    <input type="text" name="plant_name" class="form-control" placeholder="مثال: Alocasia Melo" value="<?php echo htmlspecialchars($plant_name); ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-secondary">ما هي المشكلة أو الاستفسار؟</label>
                    <select name="issue" class="form-select" required>
                        <option value="recommendation">أريد توصيات عامة للعناية بها</option>
                        <option value="yellow_leaves">الأوراق تصبح صفراء</option>
                        <option value="drooping">ذبول وتدلي الأوراق</option>
                        <option value="brown_tips">أطراف الأوراق جافة وبنية</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-bloom">احصل على النصيحة الذكية</button>
            </form>

            <?php if (!empty($advice_result)): ?>
                <div class="alert alert-success mt-4 border-0 shadow-sm" style="background-color: var(--brand-sage-light); color: var(--brand-green);">
                    <h5 class="fw-bold mb-2"><i class="bi bi-lightbulb-fill"></i> نصيحة المساعد الذكي:</h5>
                    <p class="mb-0"><?php echo $advice_result; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>