<?php
/**
 * 東京の現在時刻を表示するシンプルなウェブアプリケーション
 *
 * このファイルは、レンタルサーバーのドキュメントルート（例: public_html）に
 * 例：`index.php` として配置して使用します。
 */

// --- 関数定義 ---

/**
 * タイムゾーン 'Asia/Tokyo' で現在の時刻を取得し、指定されたフォーマットで返す関数。
 * @return string フォーマットされた現在の東京時刻
 */
function getCurrentTokyoTime(): string {
    // タイムゾーンを「東京」に設定
    // これがないと、サーバーが設定されている場所の時刻になってしまう可能性があります
    date_default_timezone_set('Asia/Tokyo');

    // 現在時刻を取得し、「Y年m月d日 H時i分s秒」の形式にフォーマット
    // Y:年, m:月, d:日, H:時(24時間制), i:分, s:秒
    $current_time = date('Y年m月d日 H時i分s秒');

    return $current_time;
}

// --- メイン処理 ---

// 実行ボタンが押されたかどうかをチェック
// `$_POST['execute']` は、ボタンの `name="execute"` から送られてくるデータです
if (isset($_POST['execute'])) {
    // ボタンが押されていたら、東京時刻を取得
    $display_time = getCurrentTokyoTime();
} else {
    // 初回アクセス時、またはボタンが押されていない場合は、表示する時刻を空にしておく
    $display_time = "実行ボタンを押してください";
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>東京 現在時刻表示アプリ</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; margin-top: 50px; background-color: #f4f4f9; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: inline-block; }
        h1 { color: #333; }
        .time-display { font-size: 2em; color: #007bff; margin-top: 20px; min-height: 40px; padding: 10px; border: 1px solid #ddd; background-color: #e9f7ff; border-radius: 4px; }
        button { padding: 10px 20px; font-size: 1.1em; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>⏰ 現在の東京時刻表示</h1>

        <div class="time-display">
            <?php echo htmlspecialchars($display_time); ?>
        </div>
        
        <hr>

        <form method="post" action="">
            <button type="submit" name="execute">
                現在の時刻を実行・更新
            </button>
        </form>
    </div>
</body>
</html>