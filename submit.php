<?php
// Accept JSON POST of answers, calculate score, store in results, return attempt id
require 'db.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if(!$data || !isset($data['answers'])){
    echo json_encode(['success'=>false,'error'=>'Invalid payload']);
    exit;
}

$answers = $data['answers']; // array of {id,choice}
// Fetch questions to compute score
$stmt = $pdo->query('SELECT id,score1,score2,score3,score4 FROM questions');
$map = [];
while($row = $stmt->fetch()){
    $map[$row['id']] = [$row['score1'],$row['score2'],$row['score3'],$row['score4']];
}

$score = 0; $max_score = 0;
foreach($answers as $a){
    $qid = (int)$a['id'];
    $choice = (int)$a['choice'];
    if(!isset($map[$qid])) continue;
    $scoresArr = $map[$qid];
    // compute max per question
    $max_score += max($scoresArr);
    if($choice >=1 && $choice <=4){
        $score += (int)$scoresArr[$choice-1];
    }
}

// store result
$ins = $pdo->prepare('INSERT INTO results (score,max_score) VALUES (?,?)');
$ins->execute([$score,$max_score]);
$attempt_id = $pdo->lastInsertId();

header('Content-Type: application/json');
echo json_encode(['success'=>true,'attempt_id'=>$attempt_id,'score'=>$score,'max_score'=>$max_score]);
exit;
?>
