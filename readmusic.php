<?php
// 设置音乐目录路径
$musicDir = './music/';
$response = [];

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
    
    // 构建响应数据
    $response['status'] = 'success';
    $response['files'] = [];
    
    foreach ($musicFiles as $file) {
        $filePath = $musicDir . $file;
        $fileSize = filesize($filePath);
        $formattedSize = round($fileSize / (1024 * 1024), 2) . ' MB';
        
        // 从文件名中提取艺术家和标题信息
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $parts = explode('-', $fileName, 2);
        $artist = count($parts) > 1 ? trim($parts[0]) : '未知艺术家';
        $title = count($parts) > 1 ? trim($parts[1]) : $fileName;
        
        $response['files'][] = [
            'path' => $filePath,
            'title' => $title,
            'artist' => $artist,
            'size' => $formattedSize
        ];
    }
} else {
    $response['status'] = 'error';
    $response['message'] = '音乐目录不存在';
}

// 设置响应头为JSON
header('Content-Type: application/json');
echo json_encode($response);
?>