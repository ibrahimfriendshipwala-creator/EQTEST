<?php
require 'db.php';
// Fetch all questions
$stmt = $pdo->query('SELECT * FROM questions ORDER BY id');
$questions = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>EQ Test — Quiz</title>
  <style>
    /* Internal CSS for quiz */
    :root{--bg:#071329;--card:#071d2b;--accent:#60a5fa;--muted:#9fb3c8}
    *{box-sizing:border-box;font-family:Inter,Roboto,Arial}
    body{margin:0;background:linear-gradient(180deg,#041226,#07132a);color:#e8f2fb}
    .wrap{max-width:920px;margin:26px auto;padding:18px}
    .card{background:rgba(255,255,255,0.03);padding:20px;border-radius:12px}
    .qnum{font-weight:700;color:var(--muted)}
    .question{font-size:20px;margin:8px 0 14px}
    .options{display:flex;flex-direction:column;gap:10px}
    .opt{padding:12px;border-radius:10px;background:rgba(255,255,255,0.02);cursor:pointer;border:1px solid rgba(255,255,255,0.03)}
    .opt.selected{outline:3px solid rgba(96,165,250,0.14);background:linear-gradient(90deg,rgba(96,165,250,0.06),transparent)}
    .controls{display:flex;justify-content:space-between;margin-top:16px}
    .btn{padding:10px 14px;border-radius:10px;border:none;font-weight:700;cursor:pointer}
    .btn.primary{background:linear-gradient(90deg,var(--accent),#7dd3fc);color:#04263b}
    .progress{height:8px;background:rgba(255,255,255,0.04);border-radius:999px;overflow:hidden;margin-top:14px}
    .progress > span{display:block;height:100%;background:linear-gradient(90deg,#7dd3fc,#60a5fa);width:0%}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card" id="quizCard">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div><strong>EQ Test</strong><div style="font-size:13px;color:var(--muted)">10 questions — take your time</div></div>
        <div id="timer" style="font-size:13px;color:var(--muted)"></div>
      </div>

      <div style="margin-top:12px">
        <div class="qnum" id="qnum">Question 1 of 10</div>
        <div class="question" id="question">Loading…</div>
        <div class="options" id="options"></div>
        <div class="progress" aria-hidden><span id="progressBar"></span></div>
        <div class="controls">
          <button class="btn" id="prevBtn">Previous</button>
          <div>
            <button class="btn" id="saveBtn">Save Answer</button>
            <button class="btn primary" id="nextBtn">Next</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // QUESTIONS loaded from PHP into JS for dynamic rendering
    const QUESTIONS = <?php echo json_encode($questions, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;

    let idx = 0;
    const responses = new Array(QUESTIONS.length).fill(null);

    const qnumEl = document.getElementById('qnum');
    const questionEl = document.getElementById('question');
    const optionsEl = document.getElementById('options');
    const progressBar = document.getElementById('progressBar');

    function render(){
      const q = QUESTIONS[idx];
      qnumEl.textContent = `Question ${idx+1} of ${QUESTIONS.length}`;
      questionEl.textContent = q.question;
      optionsEl.innerHTML = '';
      const opts = ['opt1','opt2','opt3','opt4'];
      opts.forEach((k,i)=>{
        const div = document.createElement('div');
        div.className = 'opt';
        div.dataset.optIndex = i+1;
        div.innerHTML = `<strong>${String.fromCharCode(65+i)}.</strong> ${q[k]}`;
        div.addEventListener('click', ()=>{
          // toggle selection
          const all = optionsEl.querySelectorAll('.opt');
          all.forEach(x=>x.classList.remove('selected'));
          div.classList.add('selected');
          responses[idx] = i+1; // store which option (1..4)
        });
        // preselect if already answered
        if(responses[idx] === i+1) div.classList.add('selected');
        optionsEl.appendChild(div);
      });
      updateProgress();
    }

    function updateProgress(){
      const filled = responses.filter(r=>r!==null).length;
      progressBar.style.width = ((filled/QUESTIONS.length)*100) + '%';
    }

    document.getElementById('nextBtn').addEventListener('click', ()=>{
      if(idx < QUESTIONS.length -1){ idx++; render(); }
      else finishTest();
    });
    document.getElementById('prevBtn').addEventListener('click', ()=>{ if(idx>0){ idx--; render(); }});
    document.getElementById('saveBtn').addEventListener('click', ()=>{ alert('Answer saved for this question.'); updateProgress(); });

    function finishTest(){
      // ensure at least one answer selected — optional
      if(responses.includes(null)){
        if(!confirm('Some questions are unanswered. Submit anyway?')) return;
      }

      // prepare payload: array of {id,choice}
      const payload = QUESTIONS.map((q,i)=>({id:q.id,choice: responses[i] || 0}));

      // send to submit.php via fetch, then redirect to results.php?attempt=ID
      fetch('submit.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({answers:payload})
      }).then(r=>r.json()).then(data=>{
        if(data.success && data.attempt_id){
          // redirect using JS (explicit requirement)
          window.location.href = 'results.php?attempt=' + data.attempt_id;
        } else {
          alert('Error saving results. Please try again.');
        }
      }).catch(err=>{console.error(err);alert('Network error');});
    }

    // initial render
    render();
  </script>
</body>
</html>
