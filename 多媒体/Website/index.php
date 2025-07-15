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
    <title>Traffic Information Website - Home</title>
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

        /* 导航栏样式 */
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
        }

        /* 标题区域 */
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

        /* 功能模块网格 */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        /* 模块卡片样式 */
        .module-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .module-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .module-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .module-icon {
            font-size: 3rem;
            margin-right: 1rem;
        }

        .module-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d3748;
        }

        .module-content {
            color: #4a5568;
            line-height: 1.8;
            width: 100%;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        /* 交通标志模块特殊样式 */
        .traffic-signs {
            border-left: 5px solid #e53e3e;
        }

        .signs-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
            width: 100%;
            justify-content: center;
            padding: 0 0.5rem;
        }

        .sign-item {
            text-align: center;
            padding: 0.5rem;
            background: #f7fafc;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .sign-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 0.3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .warning { background: #fed7d7; }
        .prohibition { background: #fbb6ce; }
        .mandatory { background: #bee3f8; }

        /* 公交线路模块 */
        .bus-routes {
            border-left: 5px solid #38a169;
        }

        .route-item {
            background: #f0fff4;
            border-radius: 10px;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .route-name {
            font-weight: bold;
            color: #38a169;
            margin-bottom: 0.5rem;
        }

        .route-stops {
            font-size: 0.9rem;
            color: #4a5568;
        }

        /* 安全提示模块 */
        .safety-tips {
            border-left: 5px solid #d69e2e;
        }

        .tips-categories {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
            width: 100%;
            justify-content: center;
            padding: 0 0.5rem;
        }

        .tip-category {
            text-align: center;
            padding: 0.5rem;
            background: #fffbf0;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .tip-icon {
            font-size: 1.2rem;
            margin-bottom: 0.3rem;
        }

      
        .traffic-status {
            border-left: 5px solid #3182ce;
        }

        .status-indicators {
            display: flex;
            justify-content: space-around;
            margin-top: 1rem;
        }

        .status-light {
            text-align: center;
            padding: 1rem;
        }

        .light {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 auto 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            font-weight: bold;
        }

        .green { background: #48bb78; }
        .yellow { background: #ed8936; }
        .red { background: #e53e3e; }

        /* 调查模块 */
        .survey {
            border-left: 5px solid #805ad5;
        }

        .survey-question {
            margin: 1rem 0;
        }

        .survey-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1rem 0;
        }

        .survey-option {
            padding: 1rem;
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .survey-option:hover {
            border-color: #805ad5;
            background: #faf5ff;
        }

        .survey-option.selected {
            background: #805ad5;
            color: white;
            border-color: #805ad5;
        }

        .survey-results {
            margin-top: 1rem;
            display: none;
        }

        .result-bar {
            background: #e2e8f0;
            height: 30px;
            border-radius: 15px;
            margin: 0.5rem 0;
            overflow: hidden;
        }

        .result-fill {
            height: 100%;
            background: #805ad5;
            border-radius: 15px;
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
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

            .hero h1 {
                font-size: 2rem;
            }

            .modules-grid {
                grid-template-columns: 1fr;
            }

            .signs-grid,
            .tips-categories {
                grid-template-columns: 1fr;
            }

            .survey-options {
                grid-template-columns: 1fr;
            }
        }

        /* 新增：下方两个模块居中 */
        .modules-row-center {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 2rem;
            grid-column: 1 / -1;
        }
        .modules-row-center .module-card {
            flex: 0 1 350px;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- 导航栏 -->
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
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="sign.php">Traffic Signs</a></li>
                <li><a href="route.php">Bus Routes</a></li>
                <li><a href="tips.php">Travel Tips</a></li>
                <li><a href="light.php">Traffic Status</a></li>
            </ul>
        </div>
    </nav>

    <!-- 主容器 -->
    <div class="container">
        <!-- 标题区域 -->
        <div class="hero">
            <h1>Smart Travel, Safety First</h1>
            <p>Comprehensive traffic information platform for safer and more convenient travel</p>
        </div>

        <!-- 功能模块网格 -->
        <div class="modules-grid">
            <!-- A. 交通标志识别模块 -->
            <div class="module-card traffic-signs">
                <div class="module-header">
                    <div class="module-icon">🚸</div>
                    <div class="module-title">Traffic Sign Recognition</div>
                </div>
                <div class="module-content">
                    <p>Learn and identify common traffic signs to improve road safety awareness</p>
                    <div class="signs-grid">
                        <div class="sign-item">
                            <div class="sign-icon warning">⚠️</div>
                            <div>Warning Signs</div>
                        </div>
                        <div class="sign-item">
                            <div class="sign-icon prohibition">🚫</div>
                            <div>Prohibition Signs</div>
                        </div>
                        <div class="sign-item">
                            <div class="sign-icon mandatory">ℹ️</div>
                            <div>Mandatory Signs</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- B. 公交线路展示模块 -->
            <div class="module-card bus-routes">
                <div class="module-header">
                    <div class="module-icon">🚌</div>
                    <div class="module-title">Bus Routes</div>
                </div>
                <div class="module-content">
                    <p>Popular bus route information for your daily travel</p>
                    <div class="route-item">
                        <div class="route-name">Bus Route 1</div>
                        <div class="route-stops">KLCC → Mid Valley</div>
                    </div>
                    <div class="route-item">
                        <div class="route-name">Bus Route 3</div>
                        <div class="route-stops">Batu Caves → Bukit Bintang</div>
                    </div>
                </div>
            </div>

            <!-- C. 安全出行小贴士模块 -->
            <div class="module-card safety-tips">
                <div class="module-header">
                    <div class="module-icon">💡</div>
                    <div class="module-title">Travel Safety Tips</div>
                </div>
                <div class="module-content">
                    <p>Safety recommendations for different travel modes</p>
                    <div class="tips-categories">
                        <div class="tip-category">
                            <div class="tip-icon">🚶</div>
                            <div>Pedestrian Safety</div>
                        </div>
                        <div class="tip-category">
                            <div class="tip-icon">🚴</div>
                            <div>Cycling Safety</div>
                        </div>
                        <div class="tip-category">
                            <div class="tip-icon">🚗</div>
                            <div>Driving Safety</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 新增包裹层：下方两个模块居中 -->
            <div class="modules-row-center">
                <!-- D. 模拟路况指示灯模块 -->
                <div class="module-card traffic-status">
                    <div class="module-header">
                        <div class="module-icon">🚦</div>
                        <div class="module-title">Real-time Traffic</div>
                    </div>
                    <div class="module-content">
                        <p>Current traffic conditions on major roads</p>
                        <div class="status-indicators">
                            <div class="status-light">
                                <div class="light green" id="road1">Smooth</div>
                                <div>Bukit Bintang</div>
                            </div>
                            <div class="status-light">
                                <div class="light yellow" id="road2">Moderate</div>
                                <div>Sultan Ismail</div>
                            </div>
                            <div class="status-light">
                                <div class="light red" id="road3">Heavy</div>
                                <div>Ampang</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- E. 小调查互动区模块 -->
                <div class="module-card survey">
                    <div class="module-header">
                        <div class="module-icon">📊</div>
                        <div class="module-title">Transport Survey</div>
                    </div>
                    <div class="module-content">
                        <div class="survey-question">
                            <strong>What's your most common mode of transport?</strong>
                        </div>
                        <div class="survey-options">
                            <div class="survey-option" data-value="walk">🚶 Walking</div>
                            <div class="survey-option" data-value="bike">🚴 Cycling</div>
                            <div class="survey-option" data-value="bus">🚌 Bus</div>
                            <div class="survey-option" data-value="car">🚗 Car</div>
                        </div>
                        <div class="survey-results" id="surveyResults">
                            <h4>Survey Results:</h4>
                            <div>Walking: <div class="result-bar"><div class="result-fill" id="walkResult">25%</div></div></div>
                            <div>Cycling: <div class="result-bar"><div class="result-fill" id="bikeResult">20%</div></div></div>
                            <div>Bus: <div class="result-bar"><div class="result-fill" id="busResult">35%</div></div></div>
                            <div>Car: <div class="result-bar"><div class="result-fill" id="carResult">20%</div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 路况指示灯动态更新
        function updateTrafficLights() {
            const roads = ['road1', 'road2', 'road3'];
            const statuses = [
                { class: 'green', text: 'S' },
                { class: 'yellow', text: 'M' },
                { class: 'red', text: 'H' }
            ];

            roads.forEach(roadId => {
                const road = document.getElementById(roadId);
                const randomStatus = statuses[Math.floor(Math.random() * statuses.length)];
                
                road.className = `light ${randomStatus.class}`;
                road.textContent = randomStatus.text;
            });
        }

        // 每10秒更新一次路况
        setInterval(updateTrafficLights, 10000);

        // 调查互动功能
        const surveyOptions = document.querySelectorAll('.survey-option');
        const surveyResults = document.getElementById('surveyResults');
        const results = {
            walk: 25,
            bike: 20,
            bus: 35,
            car: 20
        };

        surveyOptions.forEach(option => {
            option.addEventListener('click', function() {
                // 移除其他选项的选中状态
                surveyOptions.forEach(opt => opt.classList.remove('selected'));
                
                // 添加当前选项的选中状态
                this.classList.add('selected');
                
                // 更新选中项的数据
                const value = this.dataset.value;
                results[value] += 1;
                
                // 重新计算百分比
                const total = Object.values(results).reduce((a, b) => a + b, 0);
                
                // 更新结果显示
                document.getElementById('walkResult').style.width = `${(results.walk / total * 100)}%`;
                document.getElementById('walkResult').textContent = `${Math.round(results.walk / total * 100)}%`;
                
                document.getElementById('bikeResult').style.width = `${(results.bike / total * 100)}%`;
                document.getElementById('bikeResult').textContent = `${Math.round(results.bike / total * 100)}%`;
                
                document.getElementById('busResult').style.width = `${(results.bus / total * 100)}%`;
                document.getElementById('busResult').textContent = `${Math.round(results.bus / total * 100)}%`;
                
                document.getElementById('carResult').style.width = `${(results.car / total * 100)}%`;
                document.getElementById('carResult').textContent = `${Math.round(results.car / total * 100)}%`;
                
                // 显示结果
                surveyResults.style.display = 'block';
            });
        });

        // 模块卡片点击跳转
        document.querySelector('.traffic-signs').addEventListener('click', () => {
            window.location.href = 'sign.php';
        });

        document.querySelector('.bus-routes').addEventListener('click', () => {
            window.location.href = 'route.php';
        });

        document.querySelector('.safety-tips').addEventListener('click', () => {
            window.location.href = 'tips.php';
        });

        document.querySelector('.traffic-status').addEventListener('click', () => {
            window.location.href = 'light.php';
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