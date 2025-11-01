<?php
// index.php - single file that serves both the UI and the API.
// Save as UTF-8 without BOM.

// ----- API: when ?api=1 is present, return JSON with Tokyo time -----
if (isset($_GET['api'])) {
    // Set response headers for JSON and no-cache
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Ensure timezone is Tokyo regardless of server default
    date_default_timezone_set('Asia/Tokyo');

    $dt = new DateTime('now', new DateTimeZone('Asia/Tokyo'));

    $response = [
        'iso' => $dt->format(DateTime::ATOM),
        'date' => $dt->format('Y-m-d'),
        'time' => $dt->format('H:i:s'),
        'human' => $dt->format('Y年m月d日 H:i:s'),
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit; // important: stop here for API calls
}

// ----- Otherwise: serve the HTML UI -----
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>東京時刻表示（単一ファイル）</title>
<style>
  body { font-family: system-ui, -apple-system, "Hiragino Kaku Gothic ProN", "Noto Sans JP", Roboto, Arial; padding: 2rem; background:#f7fafc; color:#0f172a; }
  .card { background: white; border-radius: 12px; padding: 1.2rem; box-shadow: 0 6px 18px rgba(2,6,23,0.08); max-width:620px; margin: 0 auto; }
  h1 { font-size:1.2rem; margin:0 0 0.6rem 0; }
  button { padding: 0.6rem 1rem; border-radius: 8px; border: none; cursor: pointer; font-weight:600; }
  .btn-primary { background: #2563eb; color: white; }
  .time { margin-top:1rem; font-size:1.1rem; }
  .small { color:#64748b; font-size:0.9rem; margin-top:0.6rem; }
  pre { background:#f1f5f9; padding:0.8rem; border-radius:8px; overflow:auto; }
</style>
</head>
<body>
  <div class="card" role="main">
    <h1>東京の現在時刻を表示（単一ファイル）</h1>
    <p>「実行」ボタンを押すと、サーバー側（Tokyo）で算出した現在時刻を取得して表示します。</p>

    <button id="runBtn" class="btn-primary" aria-controls="result">実行</button>
    <div id="result" class="time" aria-live="polite">—</div>
    <div class="small">注意: サーバーの時刻を基準にしています（Asia/Tokyo）。</div>

    <!-- JSが無効な場合のフォールバック：同ファイルにGETでアクセス -> ?api=1 -->
    <noscript>
      <form method="GET" action="">
        <input type="hidden" name="api" value="1" />
        <button type="submit" style="margin-top:1rem;">サーバーの東京時刻を表示（JS無し）</button>
      </form>
    </noscript>

    <hr style="margin:1.2rem 0;" />
    <div class="small">
      <strong>備考（デバッグ用）</strong>
      <p>直接ブラウザで <code>?api=1</code> にアクセスすると JSON が返ります。</p>
      <pre>例: https://あなたのドメイン/index.php?api=1</pre>
    </div>
  </div>

<script>
(function(){
  const btn = document.getElementById('runBtn');
  const out = document.getElementById('result');

  async function fetchTime() {
    try {
      btn.disabled = true;
      const originalText = btn.textContent;
      btn.textContent = '取得中…';

      // Fetch the same file with ?api=1 (no cache)
      const res = await fetch('?api=1', { cache: 'no-store' });
      if (!res.ok) throw new Error('サーバーエラー: ' + res.status);

      const data = await res.json();

      // Display friendly text and ISO for debugging
      out.textContent = data.human + '（' + data.iso + '）';
    } catch (err) {
      console.error(err);
      out.textContent = 'エラーが発生しました: ' + (err.message || err);
    } finally {
      btn.disabled = false;
      btn.textContent = '実行';
    }
  }

  btn.addEventListener('click', fetchTime);
})();
</script>
</body>
</html>


