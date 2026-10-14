<style>
    .bloom-footer {
        background-color: #1F382B;
        color: #FAF8F5;
        font-family: 'Inter', sans-serif;
        padding: 50px 0 30px 0;
        margin-top: 60px;
    }

    .bloom-footer .footer-section-title {
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #B5C9BC;
        font-weight: 600;
        margin-bottom: 18px;
    }

    .bloom-footer ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .bloom-footer ul li {
        margin-bottom: 10px;
    }

    .bloom-footer ul li a {
        color: #FAF8F5;
        text-decoration: none;
        font-size: 0.95rem;
        opacity: 0.9;
        transition: opacity 0.2s;
    }

    .bloom-footer ul li a:hover {
        opacity: 1;
        text-decoration: underline;
    }

    .subscribe-box {
        display: flex;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 30px;
        overflow: hidden;
        margin-top: 15px;
        max-width: 450px;
    }

    .subscribe-box input {
        background: transparent;
        border: none;
        padding: 12px 20px;
        color: #ffffff;
        outline: none;
        width: 100%;
        font-size: 0.9rem;
    }

    .subscribe-box input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .subscribe-box button {
        background-color: #FAF8F5;
        color: #1F382B;
        border: none;
        padding: 12px 25px;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 1px;
        cursor: pointer;
        text-transform: uppercase;
        border-radius: 0 30px 30px 0;
        transition: background 0.2s;
    }

    .subscribe-box button:hover {
        background-color: #E7E0D6;
    }

    .footer-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        margin: 40px 0 25px 0;
    }

    .bottom-links a {
        color: #FAF8F5;
        text-decoration: none;
        font-size: 0.85rem;
        margin-right: 20px;
        opacity: 0.8;
    }

    .bottom-links a:hover {
        opacity: 1;
    }

    .social-icons a {
        color: #FAF8F5;
        font-size: 1.2rem;
        margin-right: 18px;
        opacity: 0.9;
        transition: opacity 0.2s;
    }

    .social-icons a:hover {
        opacity: 1;
    }
</style>

<footer class="bloom-footer">
    <div class="container">
        <!-- قسم الاشتراك بالنشرة البريدية -->
        <div class="mb-5">
            <div class="footer-section-title">STAY IN THE LOOP</div>
            <p style="font-size: 1.05rem; max-width: 500px; margin-bottom: 10px;">
                Stay in the loop with special offers, plant-parenting tips, and more.
            </p>
            <form action="#" method="POST" class="subscribe-box">
                <input type="email" placeholder="Your email" required>
                <button type="submit">SUBSCRIBE</button>
            </form>
        </div>

        <div class="row g-4">
            <!-- قسم الشركة -->
            <div class="col-6 col-md-3">
                <div class="footer-section-title">COMPANY</div>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Reviews</a></li>
                    <li><a href="#">Press</a></li>
                    <li><a href="#">Our Guarantee</a></li>
                    <li><a href="#">Corporate Gifting</a></li>
                    <li><a href="#">Trade Program</a></li>
                </ul>
            </div>

            <!-- قسم الدعم -->
            <div class="col-6 col-md-3">
                <div class="footer-section-title">SUPPORT</div>
                <ul>
                    <li><a href="#">Help + FAQs</a></li>
                    <li><a href="#">Track Your Order</a></li>
                    <li><a href="#">Shipping</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Contact Support</a></li>
                </ul>
            </div>

            <!-- قسم أسئلة النباتات -->
            <div class="col-12 col-md-4">
                <div class="footer-section-title">PLANT QUESTIONS?</div>
                <ul>
                    <li><a href="#">Plant Care Tips</a></li>
                    <li><a href="#">Plant Life Blog</a></li>
                    <li><a href="#">Contact the Grow-How® Team</a></li>
                </ul>
            </div>
        </div>

        <!-- الخط الفاصل -->
        <div class="footer-divider"></div>

        <!-- الروابط السفلية وأيقونات التواصل -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="bottom-links d-flex flex-wrap gap-2">
                <a href="#">Terms</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Do Not Sell My Information</a>
                <a href="#">Accessibility</a>
                <a href="#">Cookie Preferences</a>
            </div>

            <div class="social-icons mt-3 mt-md-0">
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-pinterest"></i></a>
                <a href="#"><i class="bi bi-tiktok"></i></a>
            </div>
        </div>

        <!-- شريط 2026 في أسفل الفوتر تماماً -->
        <div class="text-center text-light opacity-75 small mt-4 pt-3 border-top border-secondary">
            &copy; 2026 Bloom Store. All rights reserved.
        </div>
    </div>
</footer>