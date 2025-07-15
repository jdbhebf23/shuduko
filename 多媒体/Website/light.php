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
    <title>Traffic Status - Traffic Information Website</title>
    <style>
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

        /* 导航栏样式 - 与首页保持一致 */
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

        /* 主容器 - 与首页一致 */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* 标题区域 - 与首页一致 */
        .hero {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* 路况主内容区 */
        .traffic-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.8rem;
            color: #2d3748;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-title .icon {
            margin-right: 1rem;
            font-size: 2rem;
        }

        /* 路况指示灯区域 */
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .road-card {
            background: #f7fafc;
            border-radius: 15px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border-left: 5px solid #e2e8f0;
        }

        .road-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .road-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .road-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #2d3748;
        }

        .road-status {
            display: flex;
            align-items: center;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .status-icon {
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }

        /* 不同状态样式 */
        .status-free {
            background: #c6f6d5;
            color: #22543d;
            border-left-color: #48bb78;
        }
        .status-free .road-card {
            border-left-color: #48bb78;
        }

        .status-slow {
            background: #feebc8;
            color: #7b341e;
            border-left-color: #ed8936;
        }
        .status-slow .road-card {
            border-left-color: #ed8936;
        }

        .status-congested {
            background: #fed7d7;
            color: #742a2a;
            border-left-color: #e53e3e;
        }
        .status-congested .road-card {
            border-left-color: #e53e3e;
        }

        .road-details {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #4a5568;
        }

        .detail-item {
            text-align: center;
            flex: 1;
        }

        .detail-value {
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 0.3rem;
        }

        /* 地图区域 */
        .map-container {
            background: #e2e8f0;
            border-radius: 15px;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #4a5568;
            padding: 0;
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }

        .real-map-full {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 15px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        /* 更新时间 */
        .update-time {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #718096;
        }

        /* 响应式设计 - 与首页一致 */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .status-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- 导航栏 - 与首页一致 -->
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
                <li><a href="tips.php">Travel Tips</a></li>
                <li><a href="light.php" class="active">Traffic Status</a></li>
            </ul>
        </div>
    </nav>

    <!-- 主容器 -->
    <div class="container">
        <!-- 标题区域 - 与首页风格一致 -->
        <div class="hero">
            <h1>Real-time Traffic Monitoring</h1>
            <p>Latest road conditions to help you plan the best route</p>
        </div>

        <!-- 路况主内容区 -->
        <div class="traffic-container">
            <div class="section-title">
                <span class="icon">🚦</span>
                <span>Major Roads Real-time Status</span>
            </div>
            
            <div class="status-grid">
                <!-- 道路1 -->
                <div class="road-card status-free" id="road1-card">
                    <div class="road-header">
                        <div class="road-name">Jalan Bukit Bintang</div>
                        <div class="road-status">
                            <span class="status-icon">🚙</span>
                            <span>Smooth</span>
                        </div>
                    </div>
                    <div class="road-details">
                        <div class="detail-item">
                            <div>Average Speed</div>
                            <div class="detail-value">45 km/h</div>
                        </div>
                        <div class="detail-item">
                            <div>Travel Time</div>
                            <div class="detail-value">15 min</div>
                        </div>
                        <div class="detail-item">
                            <div>Congestion Index</div>
                            <div class="detail-value">1.2</div>
                        </div>
                    </div>
                </div>

                <!-- 道路2 -->
                <div class="road-card status-slow" id="road2-card">
                    <div class="road-header">
                        <div class="road-name">Jalan Sultan Ismail</div>
                        <div class="road-status">
                            <span class="status-icon">🚗</span>
                            <span>Moderate</span>
                        </div>
                    </div>
                    <div class="road-details">
                        <div class="detail-item">
                            <div>Average Speed</div>
                            <div class="detail-value">25 km/h</div>
                        </div>
                        <div class="detail-item">
                            <div>Travel Time</div>
                            <div class="detail-value">28 min</div>
                        </div>
                        <div class="detail-item">
                            <div>Congestion Index</div>
                            <div class="detail-value">2.5</div>
                        </div>
                    </div>
                </div>

                <!-- 道路3 -->
                <div class="road-card status-congested" id="road3-card">
                    <div class="road-header">
                        <div class="road-name">Jalan Ampang</div>
                        <div class="road-status">
                            <span class="status-icon">🚕</span>
                            <span>Heavy</span>
                        </div>
                    </div>
                    <div class="road-details">
                        <div class="detail-item">
                            <div>Average Speed</div>
                            <div class="detail-value">8 km/h</div>
                        </div>
                        <div class="detail-item">
                            <div>Travel Time</div>
                            <div class="detail-value">42 min</div>
                        </div>
                        <div class="detail-item">
                            <div>Congestion Index</div>
                            <div class="detail-value">4.8</div>
                        </div>
                    </div>
                </div>

                <!-- 道路4 -->
                <div class="road-card status-free" id="road4-card">
                    <div class="road-header">
                        <div class="road-name">Jalan Tun Razak</div>
                        <div class="road-status">
                            <span class="status-icon">🚙</span>
                            <span>Smooth</span>
                        </div>
                    </div>
                    <div class="road-details">
                        <div class="detail-item">
                            <div>Average Speed</div>
                            <div class="detail-value">50 km/h</div>
                        </div>
                        <div class="detail-item">
                            <div>Travel Time</div>
                            <div class="detail-value">12 min</div>
                        </div>
                        <div class="detail-item">
                            <div>Congestion Index</div>
                            <div class="detail-value">1.0</div>
                        </div>
                    </div>
                </div>

                <!-- 道路5 -->
                <div class="road-card status-slow" id="road5-card">
                    <div class="road-header">
                        <div class="road-name">Jalan Raja Laut</div>
                        <div class="road-status">
                            <span class="status-icon">🚗</span>
                            <span>Moderate</span>
                        </div>
                    </div>
                    <div class="road-details">
                        <div class="detail-item">
                            <div>Average Speed</div>
                            <div class="detail-value">20 km/h</div>
                        </div>
                        <div class="detail-item">
                            <div>Travel Time</div>
                            <div class="detail-value">35 min</div>
                        </div>
                        <div class="detail-item">
                            <div>Congestion Index</div>
                            <div class="detail-value">3.0</div>
                        </div>
                    </div>
                </div>

                <!-- 道路6 -->
                <div class="road-card status-congested" id="road6-card">
                    <div class="road-header">
                        <div class="road-name">Jalan Pudu</div>
                        <div class="road-status">
                            <span class="status-icon">🚕</span>
                            <span>Heavy</span>
                        </div>
                    </div>
                    <div class="road-details">
                        <div class="detail-item">
                            <div>Average Speed</div>
                            <div class="detail-value">5 km/h</div>
                        </div>
                        <div class="detail-item">
                            <div>Travel Time</div>
                            <div class="detail-value">50 min</div>
                        </div>
                        <div class="detail-item">
                            <div>Congestion Index</div>
                            <div class="detail-value">5.0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 地图区域 -->
            <div class="map-container">
                <img src="image/屏幕截图 2025-06-30 131831.png" alt="Real Map" class="real-map-full" />
            </div>

            <div class="update-time" id="updateTime">
                Last Updated: Getting time...
            </div>
        </div>
    </div>

    <script>
        // 道路状态数据
        const roadStatuses = [
            { 
                status: 'free', 
                label: 'Smooth', 
                icon: '🚙', 
                speedRange: [40, 60], 
                timeRange: [10, 20], 
                indexRange: [1.0, 1.5] 
            },
            { 
                status: 'slow', 
                label: 'Moderate', 
                icon: '🚗', 
                speedRange: [15, 35], 
                timeRange: [20, 35], 
                indexRange: [2.0, 3.5] 
            },
            { 
                status: 'congested', 
                label: 'Heavy', 
                icon: '🚕', 
                speedRange: [5, 15], 
                timeRange: [35, 60], 
                indexRange: [4.0, 5.0] 
            }
        ];

        // 道路列表
        const roads = [
            { id: 'road1-card', name: 'Jalan Bukit Bintang' },
            { id: 'road2-card', name: 'Jalan Sultan Ismail' },
            { id: 'road3-card', name: 'Jalan Ampang' },
            { id: 'road4-card', name: 'Jalan Tun Razak' },
            { id: 'road5-card', name: 'Jalan Raja Laut' },
            { id: 'road6-card', name: 'Jalan Pudu' }
        ];

        // 更新道路状态
        function updateRoadStatus() {
            const now = new Date();
            document.getElementById('updateTime').textContent = `Last Updated: ${now.toLocaleTimeString()}`;
            
            roads.forEach(road => {
                const card = document.getElementById(road.id);
                const randomStatus = roadStatuses[Math.floor(Math.random() * roadStatuses.length)];
                
                // 更新卡片类名
                card.className = `road-card status-${randomStatus.status}`;
                
                // 更新状态显示
                const statusElement = card.querySelector('.road-status');
                statusElement.innerHTML = `<span class="status-icon">${randomStatus.icon}</span><span>${randomStatus.label}</span>`;
                
                // 更新详细数据
                const speed = randomInRange(randomStatus.speedRange);
                const time = randomInRange(randomStatus.timeRange);
                const index = randomInRange(randomStatus.indexRange).toFixed(1);
                
                card.querySelectorAll('.detail-value')[0].textContent = `${speed} km/h`;
                card.querySelectorAll('.detail-value')[1].textContent = `${time} min`;
                card.querySelectorAll('.detail-value')[2].textContent = index;
            });
        }

        // 生成范围内的随机数
        function randomInRange(range) {
            return Math.floor(Math.random() * (range[1] - range[0] + 1)) + range[0];
        }

        // 初始更新
        updateRoadStatus();
        
        // 每30秒更新一次状态
        setInterval(updateRoadStatus, 30000);

        // 点击道路卡片显示更多详情
        document.querySelectorAll('.road-card').forEach(card => {
            card.addEventListener('click', function() {
                const roadName = this.querySelector('.road-name').textContent;
                alert(`Will show detailed traffic information for ${roadName}\n(In actual application, this would link to a detailed page or modal)`);
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