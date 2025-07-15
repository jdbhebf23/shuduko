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
    <title>Traffic Signs - Traffic Information Website</title>
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
            background: #ff6b6b;
            color: white;
            transform: translateY(-2px);
        }

        /* 主容器 */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* 页面标题 */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }

        .page-header h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        /* 搜索和筛选区域 */
        .search-filter {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.8rem 1.5rem;
            border: 2px solid #ff6b6b;
            background: white;
            color: #ff6b6b;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: #ff6b6b;
            color: white;
            transform: translateY(-2px);
        }

        /* 标志展示区域 */
        .signs-section {
            margin-bottom: 3rem;
        }

        .section-title {
            color: white;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .signs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .sign-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sign-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .sign-visual {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .sign-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .sign-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .sign-description {
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .sign-usage {
            background: #f7fafc;
            padding: 1rem;
            border-radius: 10px;
            border-left: 4px solid #ff6b6b;
        }

        .sign-usage strong {
            color: #ff6b6b;
        }

        /* 不同类型标志的颜色 */
        .warning-sign .sign-icon {
            background: linear-gradient(135deg, #ffd93d, #ff6b6b);
        }

        .prohibition-sign .sign-icon {
            background: linear-gradient(135deg, #ff6b6b, #c44569);
        }

        .mandatory-sign .sign-icon {
            background: linear-gradient(135deg, #4834d4, #686de0);
        }

        .guide-sign .sign-icon {
            background: linear-gradient(135deg, #00a8ff, #0097e6);
        }

        .info-sign .sign-icon {
            background: linear-gradient(135deg, #2ed573, #1e90ff);
        }

        /* 标志详情弹窗 */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 2rem;
            cursor: pointer;
            color: #666;
        }

        .modal-close:hover {
            color: #ff6b6b;
        }

        .modal-sign-icon {
            width: 150px;
            height: 150px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .modal-title {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 1rem;
            color: #2d3748;
        }

        .modal-description {
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .modal-examples {
            background: #f7fafc;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #ff6b6b;
        }

        .modal-examples h4 {
            color: #ff6b6b;
            margin-bottom: 1rem;
        }

        .modal-examples ul {
            list-style-type: none;
            padding-left: 0;
        }

        .modal-examples li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-examples li:last-child {
            border-bottom: none;
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

            .filter-buttons {
                justify-content: center;
            }

            .signs-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                padding: 2rem;
                margin: 1rem;
            }
        }

        /* 隐藏类 */
        .hidden {
            display: none !important;
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
                <li><a href="index.php">Home</a></li>
                <li><a href="sign.php" class="active">Traffic Signs</a></li>
                <li><a href="route.php">Bus Routes</a></li>
                <li><a href="tips.php">Travel Tips</a></li>
                <li><a href="light.php">Traffic Status</a></li>
            </ul>
        </div>
    </nav>

    <!-- 主容器 -->
    <div class="container">
        <!-- 页面标题 -->
        <div class="page-header">
            <h1>🚸 Traffic Sign Recognition</h1>
            <p>Learn and master common traffic signs to improve road safety awareness</p>
        </div>

        <!-- 搜索和筛选区域 -->
        <div class="search-filter">
            <div class="filter-buttons">
                <button class="filter-btn active" data-category="all">All Signs</button>
                <button class="filter-btn" data-category="warning">Warning Signs</button>
                <button class="filter-btn" data-category="prohibition">Prohibition Signs</button>
                <button class="filter-btn" data-category="mandatory">Mandatory Signs</button>
                <button class="filter-btn" data-category="guide">Guide Signs</button>
                <button class="filter-btn" data-category="info">Information Signs</button>
            </div>
        </div>

        <!-- 警告标志区域 -->
        <div class="signs-section" data-category="warning">
            <h2 class="section-title">⚠️ Warning Signs</h2>
            <div class="signs-grid">
                <div class="sign-card warning-sign" data-sign="crosswalk">
                    <div class="sign-visual">
                        <div class="sign-icon">🚸</div>
                        <div class="sign-name">Pedestrian Crossing</div>
                    </div>
                    <div class="sign-description">
                        Warns drivers of pedestrian crossing areas ahead. Reduce speed and be prepared to stop for pedestrians.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Schools, Shopping Malls, Hospitals
                    </div>
                </div>

                <div class="sign-card warning-sign" data-sign="curve">
                    <div class="sign-visual">
                        <div class="sign-icon">↗️</div>
                        <div class="sign-name">Sharp Curve</div>
                    </div>
                    <div class="sign-description">
                        Warns drivers of a sharp curve ahead. Reduce speed and drive carefully.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Mountain Roads, Urban Curves
                    </div>
                </div>

                <div class="sign-card warning-sign" data-sign="slope">
                    <div class="sign-visual">
                        <div class="sign-icon">📐</div>
                        <div class="sign-name">Steep Grade</div>
                    </div>
                    <div class="sign-description">
                        Warns drivers of steep road sections. Watch power when ascending, braking when descending.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Mountain Highways, Overpasses
                    </div>
                </div>

                <div class="sign-card warning-sign" data-sign="children">
                    <div class="sign-visual">
                        <div class="sign-icon">👶</div>
                        <div class="sign-name">Children Crossing</div>
                    </div>
                    <div class="sign-description">
                        Warns drivers of areas with children activity. Exercise extra caution and reduce speed.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Kindergartens, Schools, Playgrounds
                    </div>
                </div>
            </div>
        </div>

        <!-- 禁令标志区域 -->
        <div class="signs-section" data-category="prohibition">
            <h2 class="section-title">🚫 Prohibition Signs</h2>
            <div class="signs-grid">
                <div class="sign-card prohibition-sign" data-sign="no-entry">
                    <div class="sign-visual">
                        <div class="sign-icon">🚫</div>
                        <div class="sign-name">No Entry</div>
                    </div>
                    <div class="sign-description">
                        Prohibits all vehicles and pedestrians from entering. Usually used for construction or dangerous areas.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Construction Areas, Hazardous Zones
                    </div>
                </div>

                <div class="sign-card prohibition-sign" data-sign="no-parking">
                    <div class="sign-visual">
                        <div class="sign-icon">🅿️</div>
                        <div class="sign-name">No Parking</div>
                    </div>
                    <div class="sign-description">
                        Prohibits vehicle parking in this area. Violators will face fines or towing.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Main Roads, Fire Lanes
                    </div>
                </div>

                <div class="sign-card prohibition-sign" data-sign="no-horn">
                    <div class="sign-visual">
                        <div class="sign-icon">🔇</div>
                        <div class="sign-name">No Honking</div>
                    </div>
                    <div class="sign-description">
                        Prohibits use of vehicle horns to maintain quiet environment.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Hospitals, Schools, Residential Areas
                    </div>
                </div>

                <div class="sign-card prohibition-sign" data-sign="speed-limit">
                    <div class="sign-visual">
                        <div class="sign-icon">6️⃣0️⃣</div>
                        <div class="sign-name">Speed Limit</div>
                    </div>
                    <div class="sign-description">
                        Restricts maximum vehicle speed. Exceeding will result in traffic violation.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> All Roads, Special Zones
                    </div>
                </div>
            </div>
        </div>

        <!-- 指令标志区域 -->
        <div class="signs-section" data-category="mandatory">
            <h2 class="section-title">ℹ️ Mandatory Signs</h2>
            <div class="signs-grid">
                <div class="sign-card mandatory-sign" data-sign="turn-right">
                    <div class="sign-visual">
                        <div class="sign-icon">➡️</div>
                        <div class="sign-name">Turn Right</div>
                    </div>
                    <div class="sign-description">
                        Vehicles must turn right. No straight or left turn allowed.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Intersections, One-way Entrances
                    </div>
                </div>

                <div class="sign-card mandatory-sign" data-sign="go-straight">
                    <div class="sign-visual">
                        <div class="sign-icon">⬆️</div>
                        <div class="sign-name">Straight Only</div>
                    </div>
                    <div class="sign-description">
                        Vehicles must go straight. No turns allowed.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Intersections, Main Roads
                    </div>
                </div>

                <div class="sign-card mandatory-sign" data-sign="roundabout">
                    <div class="sign-visual">
                        <div class="sign-icon">🔄</div>
                        <div class="sign-name">Roundabout</div>
                    </div>
                    <div class="sign-description">
                        Vehicles must follow roundabout direction, usually counterclockwise.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Roundabout Entrances, Circular Intersections
                    </div>
                </div>

                <div class="sign-card mandatory-sign" data-sign="bike-lane">
                    <div class="sign-visual">
                        <div class="sign-icon">🚴</div>
                        <div class="sign-name">Bicycle Lane</div>
                    </div>
                    <div class="sign-description">
                        Lane reserved for bicycles. Motor vehicles not allowed.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Urban Roads, Dedicated Bike Paths
                    </div>
                </div>
            </div>
        </div>

        <!-- 指路标志区域 -->
        <div class="signs-section" data-category="guide">
            <h2 class="section-title">🗺️ Guide Signs</h2>
            <div class="signs-grid">
                <div class="sign-card guide-sign" data-sign="highway">
                    <div class="sign-visual">
                        <div class="sign-icon">🛣️</div>
                        <div class="sign-name">Highway</div>
                    </div>
                    <div class="sign-description">
                        Indicates highway direction and distance to help drivers choose correct routes.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Highway Entrances, Intersections
                    </div>
                </div>

                <div class="sign-card guide-sign" data-sign="airport">
                    <div class="sign-visual">
                        <div class="sign-icon">✈️</div>
                        <div class="sign-name">Airport Direction</div>
                    </div>
                    <div class="sign-description">
                        Shows direction and distance to airport for travelers.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> City Main Roads, Airport Vicinity
                    </div>
                </div>

                <div class="sign-card guide-sign" data-sign="city-center">
                    <div class="sign-visual">
                        <div class="sign-icon">🏙️</div>
                        <div class="sign-name">City Center</div>
                    </div>
                    <div class="sign-description">
                        Directs to city center, helping drivers navigate to urban core.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> City Outskirts, Highway Exits
                    </div>
                </div>

                <div class="sign-card guide-sign" data-sign="hospital">
                    <div class="sign-visual">
                        <div class="sign-icon">🏥</div>
                        <div class="sign-name">Hospital</div>
                    </div>
                    <div class="sign-description">
                        Shows hospital location and direction for emergency guidance.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Hospital Area Roads, Major Intersections
                    </div>
                </div>
            </div>
        </div>

        <!-- 信息标志区域 -->
        <div class="signs-section" data-category="info">
            <h2 class="section-title">📢 Information Signs</h2>
            <div class="signs-grid">
                <div class="sign-card info-sign" data-sign="gas-station">
                    <div class="sign-visual">
                        <div class="sign-icon">⛽</div>
                        <div class="sign-name">Gas Station</div>
                    </div>
                    <div class="sign-description">
                        Provides gas station location information for refueling.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Highways, City Main Roads
                    </div>
                </div>

                <div class="sign-card info-sign" data-sign="parking">
                    <div class="sign-visual">
                        <div class="sign-icon">🅿️</div>
                        <div class="sign-name">Parking</div>
                    </div>
                    <div class="sign-description">
                        Indicates parking lot location and direction.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Commercial Areas, Office Districts, Tourist Sites
                    </div>
                </div>

                <div class="sign-card info-sign" data-sign="rest-area">
                    <div class="sign-visual">
                        <div class="sign-icon">🛌</div>
                        <div class="sign-name">Rest Area</div>
                    </div>
                    <div class="sign-description">
                        Shows rest area locations for long-distance drivers.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Highways, Long-distance Routes
                    </div>
                </div>

                <div class="sign-card info-sign" data-sign="tourist">
                    <div class="sign-visual">
                        <div class="sign-icon">🗾</div>
                        <div class="sign-name">Tourist Area</div>
                    </div>
                    <div class="sign-description">
                        Indicates tourist attraction locations and directions.
                    </div>
                    <div class="sign-usage">
                        <strong>Common Locations:</strong> Tourist Areas, Travel Routes
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 详情弹窗 -->
    <div class="modal" id="signModal">
        <div class="modal-content">
            <span class="modal-close" id="modalClose">&times;</span>
            <div class="modal-sign-icon" id="modalIcon"></div>
            <h2 class="modal-title" id="modalTitle"></h2>
            <div class="modal-description" id="modalDescription"></div>
            <div class="modal-examples" id="modalExamples"></div>
        </div>
    </div>

    <script>
        // 标志详细信息数据
        const signDetails = {
            crosswalk: {
                title: "Pedestrian Crossing",
                icon: "🚸",
                description: "This is a triangular yellow warning sign used to warn drivers of pedestrian crossing areas ahead. The sign is usually set 50-100 meters before the pedestrian crossing, requiring drivers to slow down and be prepared to stop for pedestrians.",
                examples: [
                    "Pedestrian crossing at school",
                    "Pedestrian crossing at shopping malls or hospitals",
                    "Pedestrian crossing at bus stops",
                    "Pedestrian crossing at community entrances"
                ],
                iconClass: "warning-sign"
            },
            curve: {
                title: "Sharp Curve",
                icon: "↗️",
                description: "This is a warning sign used to warn drivers of a sharp curve ahead. Depending on the curve direction, the sign pattern will also change. Drivers should reduce speed and drive carefully after seeing this sign.",
                examples: [
                    "Continuous curves on mountain roads",
                    "Right angle turns on urban roads",
                    "Large arc turns on highways",
                    "Sharp turns on rural roads"
                ],
                iconClass: "warning-sign"
            },
            "no-entry": {
                title: "No Entry",
                icon: "🚫",
                description: "This is a red circular prohibition sign prohibiting all vehicles and pedestrians from entering. It is usually used for construction, traffic control, or dangerous areas. Violating this sign will result in severe traffic violation penalties.",
                examples: [
                    "Construction area closed road",
                    "Traffic accident scene",
                    "Hazardous materials transportation special road",
                    "Temporary traffic control area"
                ],
                iconClass: "prohibition-sign"
            },
            "turn-right": {
                title: "Turn Right",
                icon: "➡️",
                description: "This is a blue circular mandatory sign indicating that vehicles must turn right. This is a mandatory instruction, and violation will be considered illegal behavior. It is usually set in specific traffic control intersections.",
                examples: [
                    "Turn direction sign at one-way entrance",
                    "Traffic control intersection",
                    "Mandatory turn in special sections",
                    "Avoidance construction area detour sign"
                ],
                iconClass: "mandatory-sign"
            }
        };

        // 筛选功能
        const filterButtons = document.querySelectorAll('.filter-btn');
        const signsSections = document.querySelectorAll('.signs-section');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // 移除所有活动状态
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // 添加当前按钮的活动状态
                this.classList.add('active');
                
                const category = this.dataset.category;
                
                // 显示/隐藏相应的标志区域
                signsSections.forEach(section => {
                    if (category === 'all' || section.dataset.category === category) {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });
            });
        });

        // 弹窗功能
        const modal = document.getElementById('signModal');
        const modalClose = document.getElementById('modalClose');
        const signCards = document.querySelectorAll('.sign-card');
        const modalIcon = document.getElementById('modalIcon');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const modalExamples = document.getElementById('modalExamples');

        // 为每个标志卡片添加点击事件
        signCards.forEach(card => {
            card.addEventListener('click', function() {
                const signKey = this.dataset.sign;
                const signData = signDetails[signKey];
                
                if (signData) {
                    // 设置图标样式
                    modalIcon.innerHTML = signData.icon;
                    modalIcon.className = `modal-sign-icon ${signData.iconClass}`;
                    
                    // 设置内容
                    modalTitle.textContent = signData.title;
                    modalDescription.textContent = signData.description;
                    
                    // 设置示例
                    modalExamples.innerHTML = `
                        <h4>Usage Scenario Examples:</h4>
                        <ul>
                            ${signData.examples.map(example => `<li>• ${example}</li>`).join('')}
                        </ul>
                    `;
                    
                    // 显示弹窗
                    modal.style.display = 'flex';
                }
            });
        });

        // 关闭弹窗
        modalClose.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // 点击弹窗外部关闭
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // ESC键关闭弹窗
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
            }
        });

        // 页面加载动画
        window.addEventListener('load', function() {
            const signCards = document.querySelectorAll('.sign-card');
            signCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    card.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
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