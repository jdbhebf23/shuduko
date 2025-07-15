<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Safety Tips - Traffic Information Website</title>
    <style>
        /* 保持与首页一致的样式 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Microsoft YaHei', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #38a169 0%, #48bb78 100%);
            min-height: 100vh;
        }

        /* 导航栏样式（与首页完全一致） */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #4a5568;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* 主容器 */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            color: white;
        }

        /* 标题区域 */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* 安全提示卡片 */
        .safety-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .safety-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            color: #2d3748;
        }

        .safety-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-right: 1rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .tips-list {
            list-style-type: none;
        }

        .tip-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1rem;
            line-height: 1.7;
        }

        .tip-item::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #38a169;
            font-weight: bold;
        }

        /* 特殊卡片样式 */
        .pedestrian {
            border-top: 5px solid #e53e3e;
        }

        .pedestrian .card-icon {
            color: #e53e3e;
        }

        .cyclist {
            border-top: 5px solid #3182ce;
        }

        .cyclist .card-icon {
            color: #3182ce;
        }

        .driver {
            border-top: 5px solid #d69e2e;
        }

        .driver .card-icon {
            color: #d69e2e;
        }

        /* 紧急联系信息 */
        .emergency-info {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 3rem;
            color: #2d3748;
        }

        .emergency-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .emergency-header h2 {
            font-size: 1.8rem;
            color: #e53e3e;
        }

        .emergency-contacts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .contact-item {
            text-align: center;
            padding: 1.5rem;
            background: #f7fafc;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            transform: scale(1.03);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .contact-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #e53e3e;
        }

        .contact-name {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .contact-number {
            font-size: 1.2rem;
            color: #4a5568;
        }

        /* 响应式设计 */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .safety-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- 导航栏（与首页一致） -->
    <nav class="navbar">
        <div class="nav-container">
            <div style="display:flex;align-items:center;gap:1.2rem;">
                <span id="userDropdown" style="font-size:1.1rem;color:#222a35;font-weight:600;cursor:pointer;position:relative;">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                    <div id="logoutMenu" style="display:none;position:absolute;left:0;top:120%;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.12);padding:0.5rem 1.2rem;z-index:999;">
                        <a href="logout.php" style="color:#e53e3e;text-decoration:none;font-weight:600;">Logout</a>
                    </div>
                </span>
                <a href="index.php" class="logo">🚦 Traffic Info</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="sign.php">Traffic Signs</a></li>
                <li><a href="route.php">Bus Routes</a></li>
                <li><a href="tips.php" class="active">Travel Tips</a></li>
                <li><a href="light.php">Traffic Status</a></li>
            </ul>
        </div>
    </nav>

    <!-- 主容器 -->
    <div class="container">
        <!-- 标题区域 -->
        <div class="page-header">
            <h1>🛡️ Travel Safety Tips</h1>
            <p>Learn safety precautions for different travel modes to protect yourself and others</p>
        </div>

        <!-- 安全提示卡片 -->
        <div class="safety-cards">
            <!-- 行人安全 -->
            <div class="safety-card pedestrian">
                <div class="card-header">
                    <div class="card-icon">🚶</div>
                    <div class="card-title">Pedestrian Safety</div>
                </div>
                <ul class="tips-list">
                    <li class="tip-item">Use crosswalks or pedestrian bridges and obey traffic signals</li>
                    <li class="tip-item">Don't use phones or wear headphones while crossing roads</li>
                    <li class="tip-item">Wear light-colored or reflective clothing at night</li>
                    <li class="tip-item">Watch for vehicles and don't rush onto the road</li>
                    <li class="tip-item">Children should be accompanied by adults when crossing roads</li>
                    <li class="tip-item">Don't walk on vehicle lanes or bicycle paths</li>
                </ul>
            </div>

            <!-- 骑行者安全 -->
            <div class="safety-card cyclist">
                <div class="card-header">
                    <div class="card-icon">🚴</div>
                    <div class="card-title">Cyclist Safety</div>
                </div>
                <ul class="tips-list">
                    <li class="tip-item">Wear a certified helmet and use lights at night</li>
                    <li class="tip-item">Ride in bicycle lanes or keep to the right if none available</li>
                    <li class="tip-item">Obey traffic signals, don't run red lights or ride against traffic</li>
                    <li class="tip-item">Signal before turning or changing lanes, check traffic behind</li>
                    <li class="tip-item">Maintain your bicycle, regularly check brakes and tires</li>
                    <li class="tip-item">Don't ride side by side or carry passengers</li>
                    <li class="tip-item">Reduce speed in rain and avoid sudden braking</li>
                </ul>
            </div>

            <!-- 司机安全 -->
            <div class="safety-card driver">
                <div class="card-header">
                    <div class="card-icon">🚗</div>
                    <div class="card-title">Driver Safety</div>
                </div>
                <ul class="tips-list">
                    <li class="tip-item">Wear seatbelts and ensure passengers do the same</li>
                    <li class="tip-item">Follow traffic rules, no speeding, drunk driving, or driving when tired</li>
                    <li class="tip-item">Maintain safe distance, avoid sudden braking</li>
                    <li class="tip-item">Signal before turning or changing lanes, check blind spots</li>
                    <li class="tip-item">Give way to pedestrians, especially in school zones and crosswalks</li>
                    <li class="tip-item">Don't use phone while driving, avoid distractions</li>
                    <li class="tip-item">Regular vehicle maintenance, check brakes and lights</li>
                    <li class="tip-item">Reduce speed in bad weather, keep greater safety distance</li>
                </ul>
            </div>
        </div>

        <!-- 紧急联系信息 -->
        <div class="emergency-info">
            <div class="emergency-header">
                <h2>🚨 Emergency Contacts</h2>
                <p>Contact relevant departments in case of emergency</p>
            </div>
            <div class="emergency-contacts">
                <div class="contact-item">
                    <div class="contact-icon">🚔</div>
                    <div class="contact-name">Police</div>
                    <div class="contact-number">999</div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🚑</div>
                    <div class="contact-name">Ambulance</div>
                    <div class="contact-number">999</div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🚒</div>
                    <div class="contact-name">Fire & Rescue</div>
                    <div class="contact-number">994</div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <div class="contact-name">Tourist Helpline</div>
                    <div class="contact-number">1-300-88-5050</div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🚗</div>
                    <div class="contact-name">Road Assistance</div>
                    <div class="contact-number">1-800-88-9333</div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🏥</div>
                    <div class="contact-name">KL General Hospital</div>
                    <div class="contact-number">03-2615-5555</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 保持与首页一致的交互效果
        document.querySelectorAll('.safety-card').forEach(card => {
            card.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const userDropdown = document.getElementById('userDropdown');
            const logoutMenu = document.getElementById('logoutMenu');
            let menuOpen = false;
            userDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                menuOpen = !menuOpen;
                logoutMenu.style.display = menuOpen ? 'block' : 'none';
            });
            document.addEventListener('click', function() {
                logoutMenu.style.display = 'none';
                menuOpen = false;
            });
        });
    </script>
</body>
</html>