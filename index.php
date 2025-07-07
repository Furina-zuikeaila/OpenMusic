<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>简约音乐播放器</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #5a67d8;
            --primary-light: #a3bffa;
            --primary-dark: #4c51bf;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text: #2d3748;
            --text-light: #718096;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            --radius: 16px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }
        
        body {
            background: var(--background);
            color: var(--text);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
        }
        
        @media (max-width: 900px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
        
        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        
        .header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .header h2 i {
            color: var(--primary);
        }
        
        /* 搜索区域样式 */
        .search-container {
            position: relative;
            margin-bottom: 20px;
        }
        
        .search-container i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        .search-input {
            width: 100%;
            padding: 14px 20px 14px 50px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            background: var(--card-bg);
            transition: all 0.3s ease;
            color: var(--text);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.15);
        }
        
        /* 文件列表样式 */
        .files-container {
            flex: 1;
            overflow-y: auto;
            padding-right: 5px;
            max-height: 450px;
        }
        
        .file-item {
            display: flex;
            align-items: center;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 10px;
            background: var(--card-bg);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
            gap: 15px;
        }
        
        .file-item:hover {
            background: #f5f7ff;
            border-color: var(--primary-light);
        }
        
        .file-item.active {
            background: rgba(90, 103, 216, 0.08);
            border-color: var(--primary-light);
        }
        
        .file-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(90, 103, 216, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }
        
        .file-info {
            flex: 1;
            min-width: 0;
        }
        
        .file-name {
            font-size: 1.05rem;
            font-weight: 500;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .file-details {
            display: flex;
            gap: 12px;
            font-size: 0.85rem;
            color: var(--text-light);
        }
        
        .download-btn {
            background: transparent;
            border: none;
            color: var(--text-light);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .download-btn:hover {
            background: rgba(90, 103, 216, 0.1);
            color: var(--primary);
        }
        
        /* 播放器区域样式 */
        .player-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px 0;
        }
        
        .album-art {
            width: 240px;
            height: 240px;
            margin: 0 auto 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e0e7ff, #d6e1ff);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(90, 103, 216, 0.15);
            border: 8px solid rgba(255, 255, 255, 0.6);
        }
        
        .album-art i {
            font-size: 5rem;
            color: var(--primary);
            opacity: 0.8;
        }
        
        .track-info {
            margin-bottom: 30px;
            max-width: 100%;
        }
        
        .track-name {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 20px;
        }
        
        .track-artist {
            font-size: 1.1rem;
            color: var(--text-light);
        }
        
        .progress-container {
            width: 100%;
            background: #edf2f7;
            border-radius: 10px;
            height: 6px;
            margin: 25px 0 10px;
            cursor: pointer;
            position: relative;
        }
        
        .progress-bar {
            background: var(--primary);
            border-radius: 10px;
            height: 100%;
            width: 0%;
            position: relative;
            transition: width 0.1s linear;
        }
        
        .progress-time {
            display: flex;
            justify-content: space-between;
            width: 100%;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 30px 0 20px;
        }
        
        .control-btn {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text);
            font-size: 1.2rem;
            box-shadow: var(--shadow);
        }
        
        .control-btn:hover {
            background: #f5f7ff;
            transform: scale(1.05);
            color: var(--primary);
            border-color: var(--primary-light);
        }
        
        .control-btn.play-pause {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
        }
        
        .control-btn.play-pause:hover {
            background: var(--primary-dark);
            color: white;
        }
        
        .volume-container {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
            max-width: 300px;
            margin-top: 20px;
        }
        
        .volume-slider {
            flex: 1;
            -webkit-appearance: none;
            height: 5px;
            background: #edf2f7;
            border-radius: 5px;
            outline: none;
        }
        
        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--primary);
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .no-files, .error {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
            font-size: 1.1rem;
        }
        
        .no-files i, .error i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #cbd5e0;
            display: block;
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            font-size: 0.9rem;
            color: var(--text-light);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 左侧文件列表 -->
        <div class="card">
            <div class="header">
                <h2><i class="fas fa-music"></i> 音乐库</h2>
            </div>
            
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="搜索歌曲或艺术家...">
            </div>
            
            <div class="files-container">
                <?php
                // 设置音乐目录路径
                $musicDir = './music/';
                
                // 检查目录是否存在
                if (is_dir($musicDir)) {
                    // 扫描目录获取文件
                    $files = scandir($musicDir);
                    
                    // 过滤文件 - 只保留音乐文件
                    $musicFiles = array_filter($files, function($file) {
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        $allowedExt = ['mp3', 'wav', 'ogg', 'flac'];
                        return in_array(strtolower($ext), $allowedExt);
                    });
                    
                    // 排序文件
                    sort($musicFiles);
                    
                    // 显示文件列表
                    if (count($musicFiles) > 0) {
                        foreach ($musicFiles as $file) {
                            $filePath = $musicDir . $file;
                            $fileSize = filesize($filePath);
                            $formattedSize = round($fileSize / (1024 * 1024), 2) . ' MB';
                            
                            // 从文件名中提取艺术家和标题信息
                            $fileName = pathinfo($file, PATHINFO_FILENAME);
                            $parts = explode('-', $fileName, 2);
                            $artist = count($parts) > 1 ? trim($parts[0]) : '未知艺术家';
                            $title = count($parts) > 1 ? trim($parts[1]) : $fileName;
                            
                            echo '<div class="file-item" data-src="' . $filePath . '" data-artist="' . htmlspecialchars($artist) . '" data-title="' . htmlspecialchars($title) . '">';
                            echo '    <div class="file-icon"><i class="fas fa-file-audio"></i></div>';
                            echo '    <div class="file-info">';
                            echo '        <div class="file-name">' . htmlspecialchars($title) . '</div>';
                            echo '        <div class="file-details">';
                            echo '            <span class="file-artist">' . htmlspecialchars($artist) . '</span>';
                            echo '            <span>•</span>';
                            echo '            <span class="file-size">' . $formattedSize . '</span>';
                            echo '        </div>';
                            echo '    </div>';
                            echo '    <button class="download-btn" title="下载" onclick="downloadMusic(\'' . $filePath . '\', \'' . htmlspecialchars($title) . '\')">';
                            echo '        <i class="fas fa-download"></i>';
                            echo '    </button>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="no-files">';
                        echo '    <i class="fas fa-music"></i>';
                        echo '    <p>没有找到音乐文件</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="error">';
                    echo '    <i class="fas fa-exclamation-triangle"></i>';
                    echo '    <p>音乐目录不存在</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        
        <!-- 右侧播放器 -->
        <div class="card">
            <div class="header">
                <h2><i class="fas fa-play-circle"></i> 正在播放</h2>
            </div>
            
            <div class="player-content">
                <div class="album-art">
                    <i class="fas fa-music"></i>
                </div>
                
                <div class="track-info">
                    <div class="track-name">选择歌曲开始播放</div>
                    <div class="track-artist">—</div>
                </div>
                
                <div class="progress-container" id="progressContainer">
                    <div class="progress-bar" id="progressBar"></div>
                </div>
                <div class="progress-time">
                    <span id="currentTime">0:00</span>
                    <span id="duration">0:00</span>
                </div>
                
                <div class="controls">
                    <button class="control-btn" id="prevBtn" title="上一首">
                        <i class="fas fa-step-backward"></i>
                    </button>
                    <button class="control-btn play-pause" id="playPauseBtn" title="播放">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="control-btn" id="nextBtn" title="下一首">
                        <i class="fas fa-step-forward"></i>
                    </button>
                </div>
                
                <div class="volume-container">
                    <i class="fas fa-volume-up" style="color: var(--text-light);"></i>
                    <input type="range" class="volume-slider" id="volumeSlider" min="0" max="1" step="0.01" value="0.7">
                    <i class="fas fa-volume-high" style="color: var(--text-light);"></i>
                </div>
            </div>
        </div>
    </div>
    
    <audio id="audioPlayer"></audio>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 获取DOM元素
            const audioPlayer = document.getElementById('audioPlayer');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const progressBar = document.getElementById('progressBar');
            const progressContainer = document.getElementById('progressContainer');
            const currentTimeEl = document.getElementById('currentTime');
            const durationEl = document.getElementById('duration');
            const volumeSlider = document.getElementById('volumeSlider');
            const trackName = document.querySelector('.track-name');
            const trackArtist = document.querySelector('.track-artist');
            const fileItems = document.querySelectorAll('.file-item');
            const searchInput = document.getElementById('searchInput');
            
            // 当前播放状态
            let isPlaying = false;
            let currentTrackIndex = 0;
            const tracks = Array.from(fileItems);
            
            // 格式化时间 (秒 → 分:秒)
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                seconds = Math.floor(seconds % 60);
                return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
            
            // 更新进度条
            function updateProgress() {
                const { currentTime, duration } = audioPlayer;
                const progressPercent = (currentTime / duration) * 100;
                progressBar.style.width = `${progressPercent}%`;
                
                currentTimeEl.textContent = formatTime(currentTime);
                durationEl.textContent = formatTime(duration);
            }
            
            // 设置进度条
            function setProgress(e) {
                const width = this.clientWidth;
                const clickX = e.offsetX;
                const duration = audioPlayer.duration;
                
                audioPlayer.currentTime = (clickX / width) * duration;
            }
            
            // 播放/暂停切换
            function togglePlay() {
                if (isPlaying) {
                    audioPlayer.pause();
                    playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                } else {
                    audioPlayer.play();
                    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                }
                isPlaying = !isPlaying;
            }
            
            // 加载并播放曲目
            function loadTrack(index) {
                const track = tracks[index];
                const src = track.getAttribute('data-src');
                const artist = track.getAttribute('data-artist');
                const title = track.getAttribute('data-title');
                
                // 更新UI
                trackName.textContent = title;
                trackArtist.textContent = artist;
                
                // 移除之前的active类
                tracks.forEach(t => t.classList.remove('active'));
                // 添加active类到当前曲目
                track.classList.add('active');
                
                // 设置音频源
                audioPlayer.src = src;
                audioPlayer.load();
                
                // 播放
                isPlaying = false;
                togglePlay();
                
                // 滚动到选中的项目
                track.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // 下一首
            function nextTrack() {
                currentTrackIndex++;
                if (currentTrackIndex >= tracks.length) {
                    currentTrackIndex = 0;
                }
                loadTrack(currentTrackIndex);
            }
            
            // 上一首
            function prevTrack() {
                currentTrackIndex--;
                if (currentTrackIndex < 0) {
                    currentTrackIndex = tracks.length - 1;
                }
                loadTrack(currentTrackIndex);
            }
            
            // 搜索功能
            function searchTracks() {
                const searchTerm = searchInput.value.toLowerCase();
                
                tracks.forEach(track => {
                    const title = track.getAttribute('data-title').toLowerCase();
                    const artist = track.getAttribute('data-artist').toLowerCase();
                    
                    if (title.includes(searchTerm) || artist.includes(searchTerm)) {
                        track.style.display = 'flex';
                    } else {
                        track.style.display = 'none';
                    }
                });
            }
            
            // 下载音乐
            window.downloadMusic = function(filePath, fileName) {
                // 创建一个隐藏的下载链接并触发点击
                const link = document.createElement('a');
                link.href = filePath;
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };
            
            // 事件监听器
            playPauseBtn.addEventListener('click', togglePlay);
            prevBtn.addEventListener('click', prevTrack);
            nextBtn.addEventListener('click', nextTrack);
            audioPlayer.addEventListener('timeupdate', updateProgress);
            audioPlayer.addEventListener('ended', nextTrack);
            progressContainer.addEventListener('click', setProgress);
            volumeSlider.addEventListener('input', () => {
                audioPlayer.volume = volumeSlider.value;
            });
            searchInput.addEventListener('input', searchTracks);
            
            // 为每个文件项添加点击事件
            tracks.forEach((track, index) => {
                track.addEventListener('click', () => {
                    currentTrackIndex = index;
                    loadTrack(index);
                });
            });
            
            // 自动播放第一首（如果有音乐文件）
            if (tracks.length > 0) {
                loadTrack(0);
            }
        });
    </script>
</body>
</html>