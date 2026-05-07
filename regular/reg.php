<?php
//1
function getFileExtension($filename) {
    if (preg_match('/\.([a-zA-Z0-9]+)$/', $filename, $matches)) {
        return $matches[1];
    }
    return null;
}
//2
function checkFileType($filename) {
    $types = [
        'archive' => '/\.(zip|rar|7z|tar|gz|bz2)$/i',
        'audio'   => '/\.(mp3|wav|ogg|flac|aac|m4a)$/i',
        'video'   => '/\.(mp4|avi|mkv|mov|wmv|flv|webm)$/i',
        'image'   => '/\.(jpg|jpeg|png|gif|bmp|svg|webp)$/i'
    ];
    
    $result = [];
    foreach ($types as $type => $pattern) {
        $result[$type] = preg_match($pattern, $filename) === 1;
    }
    return $result;
}

// 3
function getTitleFromHtml($html) {
    if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches)) {
        return trim($matches[1]);
    }
    return null;
}

// 4
function getAllLinksFromHtml($html) {
    preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/is', $html, $matches);
    return $matches[1];
}

// 5
function getAllImageSrcFromHtml($html) {
    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/is', $html, $matches);
    return $matches[1];
}

// 6
function highlightText($text, $search) {
    return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<strong>$1</strong>', $text);
}

// 7
function replaceEmoticons($text) {
    $emoticons = [
        '/:\)/' => '<img src="smile.png" alt=":)">',
        '/;\)/' => '<img src="wink.png" alt=";)">',
        '/:\(/' => '<img src="sad.png" alt=":(">'
    ];
    
    return preg_replace(array_keys($emoticons), array_values($emoticons), $text);
}

// 8
function removeExtraSpaces($string) {
    return preg_replace('/\s+/', ' ', trim($string));
}


?>