<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Routes - Traffic Information Website</title>
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

        /* 公交线路卡片 */
        .bus-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            color: #2d3748;
        }

        .bus-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1rem;
            gap: 2rem;
        }

        .bus-icon {
            font-size: 2.5rem;
            margin-right: 1rem;
            color: #38a169;
        }

        .bus-title {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .bus-meta {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #4a5568;
        }

        .bus-meta span {
            display: flex;
            align-items: center;
        }

        .bus-meta i {
            margin-right: 0.3rem;
        }

        /* 站点列表 */
        .stops-container {
            display: flex;
            margin-top: 1.5rem;
            align-items: flex-start;
            min-height: 220px;
            align-items: center;
        }

        .stop-list {
            flex: 1;
            position: relative;
            padding-left: 1.5rem;
            display: flex;
            flex-direction: column;
        }

        .stop-item {
            position: relative;
            padding: 0.8rem 0;
            padding-left: 1.5rem;
        }

        .stop-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #38a169;
        }

        .stop-name {
            font-weight: 500;
        }

        .stop-time {
            font-size: 0.8rem;
            color: #718096;
            margin-top: 0.2rem;
        }

        .first-stop::before {
            background: #e53e3e;
        }

        .last-stop::before {
            background: #3182ce;
        }

        .bus-image {
            margin-left: 1.5rem;
            height: 100%;
            max-width: 600px;
            min-width: 300px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            object-fit: cover;
            display: block;
        }

        /* 线路地图 */
        .route-map {
            margin-top: 2rem;
            height: 200px;
            background: #f7fafc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #718096;
            font-weight: bold;
            position: relative;
            overflow: hidden;
        }

        .route-map::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, #f7fafc 25%, #e2e8f0 25%, #e2e8f0 50%, #f7fafc 50%, #f7fafc 75%, #e2e8f0 75%, #e2e8f0 100%);
            background-size: 20px 20px;
            opacity: 0.3;
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

            .bus-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .bus-icon {
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 900px) {
            .stops-container {
                flex-direction: column;
                align-items: stretch;
            }
            .bus-image {
                width: 100%;
                max-width: 100%;
                min-width: 200px;
                height: 220px;
                margin-left: 0;
                margin-top: 1rem;
                object-fit: cover;
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
                <li><a href="route.php" class="active">Bus Routes</a></li>
                <li><a href="tips.php">Travel Tips</a></li>
                <li><a href="light.php">Traffic Status</a></li>
            </ul>
        </div>
    </nav>

    <!-- 主容器 -->
    <div class="container">
        <!-- 标题区域 -->
        <div class="page-header">
            <h1>🚌 Bus Route Information</h1>
            <p>View popular city bus routes to plan your journey</p>
        </div>

        <!-- 公交线路1 -->
        <div class="bus-card">
            <div class="bus-header">
                <div class="bus-icon">K1</div>
                <div>
                    <div class="bus-title">K1 (KLCC ⇌ Mid Valley)</div>
                    <div class="bus-meta">
                        <span><i>⏱️</i> Operating Hours: 6:00-23:00</span>
                        <span><i>↔️</i> Frequency: 10-15 mins</span>
                        <span><i>💰</i> Fare: 2.5 RM</span>
                    </div>
                </div>
            </div>

            <div class="stops-container">
                <div class="stop-list">
                    <div class="stop-item first-stop">
                        <div class="stop-name">KLCC (Kuala Lumpur City Centre)</div>
                        <div class="stop-time">First: 6:00 | Last: 22:30</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">Ampang Park</div>
                        <div class="stop-time">Est. 6:15 | 22:45</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">KL Sentral (Central Station)</div>
                        <div class="stop-time">Est. 6:30 | 23:00</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">Bangsar</div>
                        <div class="stop-time">Est. 6:45 | 23:15</div>
                    </div>
                    <div class="stop-item last-stop">
                        <div class="stop-name">Mid Valley</div>
                        <div class="stop-time">Est. 7:00 | 23:30</div>
                    </div>
                </div>
                <img class="bus-image" src="image/1.jpg" alt="K1 Bus" />
            </div>
        </div>

        <!-- 公交线路2 -->
        <div class="bus-card">
            <div class="bus-header">
                <div class="bus-icon">K3</div>
                <div>
                    <div class="bus-title">K3 (Batu Caves ⇌ Bukit Bintang)</div>
                    <div class="bus-meta">
                        <span><i>⏱️</i> Operating Hours: 5:30-22:30</span>
                        <span><i>↔️</i> Frequency: 12-15 mins</span>
                        <span><i>💰</i> Fare: 3 RM</span>
                    </div>
                </div>
            </div>

            <div class="stops-container">
                <div class="stop-list">
                    <div class="stop-item first-stop">
                        <div class="stop-name">Batu Caves</div>
                        <div class="stop-time">First: 5:30 | Last: 22:00</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">Sentul Timur</div>
                        <div class="stop-time">Est. 5:45 | 22:15</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">Chow Kit</div>
                        <div class="stop-time">Est. 6:00 | 22:30</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">Masjid Jamek</div>
                        <div class="stop-time">Est. 6:15 | 22:45</div>
                    </div>
                    <div class="stop-item last-stop">
                        <div class="stop-name">Bukit Bintang</div>
                        <div class="stop-time">Est. 6:30 | 23:00</div>
                    </div>
                </div>
                <img class="bus-image" src="image/2.jpg" alt="K3 Bus" />
            </div>
        </div>

        <!-- 公交线路3 -->
        <div class="bus-card">
            <div class="bus-header">
                <div class="bus-icon">K5</div>
                <div>
                    <div class="bus-title">K5 (TBS ⇌ Sunway Pyramid)</div>
                    <div class="bus-meta">
                        <span><i>⏱️</i> Operating Hours: 6:00-22:00</span>
                        <span><i>↔️</i> Frequency: 15-20 mins</span>
                        <span><i>💰</i> Fare: 4 RM</span>
                    </div>
                </div>
            </div>

            <div class="stops-container">
                <div class="stop-list">
                    <div class="stop-item first-stop">
                        <div class="stop-name">TBS (Terminal Bersepadu Selatan)</div>
                        <div class="stop-time">First: 6:00 | Last: 21:30</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">Salak Selatan</div>
                        <div class="stop-time">Est. 6:15 | 21:45</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">OUG</div>
                        <div class="stop-time">Est. 6:30 | 22:00</div>
                    </div>
                    <div class="stop-item">
                        <div class="stop-name">Sunway University</div>
                        <div class="stop-time">Est. 6:45 | 22:15</div>
                    </div>
                    <div class="stop-item last-stop">
                        <div class="stop-name">Sunway Pyramid</div>
                        <div class="stop-time">Est. 7:00 | 22:30</div>
                    </div>
                </div>
                <img class="bus-image" src="image/3.webp" alt="K5 Bus" />
            </div>
        </div>
    </div>

    <script>
        // 保持与首页一致的交互效果
        document.querySelectorAll('.stop-item').forEach(item => {
            item.addEventListener('mouseover', function() {
                this.style.transform = 'translateX(5px)';
            });
            item.addEventListener('mouseout', function() {
                this.style.transform = 'translateX(0)';
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