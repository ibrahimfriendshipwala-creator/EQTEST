<?php
require 'db.php';
$attempt = isset($_GET['attempt']) ? (int)$_GET['attempt'] : 0;
if(!$attempt){ echo "<p>Invalid attempt ID.</p>"; exit; }
$stmt = $pdo->prepare('SELECT * FROM results WHERE id = ? LIMIT 1');
$stmt->execute([$attempt]);
$res = $stmt->fetch();
if(!$res){ echo "<p>Result not found.</p>"; exit; }
$score = (int)$res['score'];
$max = (int)$res['max_score'];
$percent = $max>0 ? round(($score/$max)*100) : 0;

// Simple feedback tiers
if($percent >= 85){ $tier = 'Excellent'; $advice = 'You show very strong emotional intelligence. Keep practicing empathy and self-reflection.'; }
elseif($percent >= 65){ $tier = 'Good'; $advice = 'You have good emotional skills. Focus on targeted improvement in self-awareness and regulation.'; }
elseif($percent >= 40){ $tier = 'Average'; $advice = 'You have room to grow. Practice active listening and naming emotions.'; }
else{ $tier = 'Needs Work'; $advice = 'Try journaling, pause before reacting, and practice empathy exercises.'; }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>EQ Test — Results</title>
  <style>
    :root{--bg:#081226;--card:#071528;--accent:#7dd3fc;--muted:#9fb3c8}
    *{box-sizing:border-box;font-family:Inter,Arial}
    body{margin:0;background:linear-gradient(180deg,#051024,#07142a);color:#e8f6ff}
    .wrap{max-width:820px;margin:30px auto;padding:20px}
    .card{background:rgba(255,255,255,0.03);padding:22px;border-radius:12px}
    .score{font-size:48px;font-weight:800;margin:6px 0}
    .tier{font-size:20px;margin:6px 0;color:var(--accent)}
    .advice{color:var(--muted);margin-top:10px}
    .actions{margin-top:16px}
    .btn{padding:10px 14px;border-radius:10px;border:none;font-weight:700;cursor:pointer;margin-right:8px}
    .btn.primary{background:linear-gradient(90deg,var(--accent),#60a5fa);color:#04263b}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h2>Your EQ Test Result</h2>
      <div class="score"><?php echo $score . ' / ' . $max; ?></div>
      <div class="tier"><?php echo $tier . ' — ' . $percent . '%'; ?></div>
      <p class="advice"><?php echo htmlspecialchars($advice); ?></p>

      <div style="margin-top:12px">
        <strong>Recommendations</strong>
        <ul class="advice">
          <li>Practice naming emotions daily (self-awareness).</li>
          <li>Listen actively: ask open questions and paraphrase (empathy).</li>
          <li>Use breathing or pausing before reacting (emotion regulation).</li>
        </ul>
      </div>

      <div class="actions">
        <button class="btn" onclick="window.location.href='quiz.php'">Retake Test</button>
        <button class="btn primary" id="shareBtn">Share Result</button>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('shareBtn').addEventListener('click', ()=>{
      const text = `I just scored <?php echo $percent; ?>% on an EQ test!`;
      if(navigator.share){
        navigator.share({title:'My EQ Result',text});
      } else {
        // fallback: copy to clipboard
        navigator.clipboard.writeText(text).then(()=>alert('Result copied to clipboard.'))
      }
    });
  </script>
</body>
</html>
