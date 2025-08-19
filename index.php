<?php /* Homepage */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>EQ Test — Measure your Emotional Intelligence</title>
  <style>
    /* Internal CSS — attractive, modern look */
    :root{--bg:#0f1724;--card:#0b1220;--accent:#7dd3fc;--muted:#94a3b8}
    *{box-sizing:border-box;font-family:Inter,Segoe UI,Roboto,Arial,sans-serif}
    body{margin:0;background:linear-gradient(180deg,#071029 0%,#091426 100%);color:#e6eef6;}
    .wrap{max-width:980px;margin:40px auto;padding:24px}
    .hero{display:flex;gap:24px;align-items:center}
    .card{background:rgba(255,255,255,0.03);padding:22px;border-radius:14px;box-shadow:0 6px 30px rgba(2,6,23,0.6)}
    h1{margin:0 0 6px;font-size:28px}
    p.lead{color:var(--muted);margin:0 0 16px}
    .start-btn{display:inline-block;padding:12px 18px;border-radius:10px;background:linear-gradient(90deg,var(--accent),#60a5fa);color:#04263b;text-decoration:none;font-weight:700;cursor:pointer;border:none}
    .features{margin-top:18px;color:var(--muted);font-size:14px}
    footer{margin-top:26px;color:var(--muted);font-size:13px}
    @media(max-width:720px){.hero{flex-direction:column}}
  </style>\head>
<body>
  <div class="wrap">
    <div class="hero">
      <div class="card" style="flex:1">
        <h1>Emotional Intelligence Test (EQ)</h1>
        <p class="lead">Understand your emotional strengths and where you can improve. This short test measures self-awareness, empathy, and emotional regulation.</p>
        <ul class="features">
          <li>10 dynamic multiple-choice questions</li>
          <li>Instant scoring and tailored feedback</li>
          <li>Responsive — works on mobile & desktop</li>
        </ul>
        <div style="margin-top:18px">
          <button class="start-btn" id="startBtn">Start Test</button>
        </div>
      </div>
      <div class="card" style="width:320px;text-align:center">
        <h3 style="margin-top:0">How it works</h3>
        <p class="lead" style="font-size:14px">You’ll answer 10 questions. Each option has a score. After submitting, you’ll see your EQ score and recommendations.</p>
      </div>
    </div>
    <footer>Made with ❤️ — EQ Test Platform</footer>
  </div>

  <script>
    // Use JS to redirect (no PHP header redirects)
    document.getElementById('startBtn').addEventListener('click', function(){
      // simple fade then redirect
      window.location.href = 'quiz.php';
    });
  </script>
</body>
</html>
