<?php
// PHP_EOL はOS依存の改行コードです。HTMLの出力では <br> や CSS を使います。
// このファイルはWebページとして動作するため、HTML出力に焦点を当てます。

// ------------------------------------
// 1. 定数と変数の初期化
// ------------------------------------

// じゃんけんの手の定義 (0: グー, 1: チョキ, 2: パー)
define('ROCK', 0);
define('SCISSORS', 1);
define('PAPER', 2);

// 手の日本語名
$hand_names = [
    ROCK => 'グー',
    SCISSORS => 'チョキ',
    PAPER => 'パー',
];

$player_hand = null;
$computer_hand = null;
$result_message = '';

// ------------------------------------
// 2. ゲームロジック
// ------------------------------------

// ユーザーからの入力があるか確認
if (isset($_POST['player_hand'])) {
    // プレイヤーの手を取得し、整数型に変換
    $player_hand = (int)$_POST['player_hand'];

    // コンピュータの手をランダムに決定
    // rand(min, max) は min から max までの整数をランダムに生成します。
    $computer_hand = rand(ROCK, PAPER);

    // 勝敗判定ロジック
    // (プレイヤーの手 - コンピュータの手 + 3) % 3 の結果で判定します
    // 0: あいこ, 1: プレイヤーの負け, 2: プレイヤーの勝ち
    $diff = ($player_hand - $computer_hand + 3) % 3;

    $player_hand_name = $hand_names[$player_hand];
    $computer_hand_name = $hand_names[$computer_hand];

    // 結果メッセージの生成
    $result_message .= "あなたは **{$player_hand_name}** を出しました。<br>";
    $result_message .= "コンピュータは **{$computer_hand_name}** を出しました。<br><br>";

    if ($diff === 0) {
        $result_message .= '<span class="draw">**引き分け（あいこ）です！**</span>';
    } elseif ($diff === 1) {
        // 1 は (0-2+3)%3=1, (1-0+3)%3=1, (2-1+3)%3=1 の場合で、すべてプレイヤーの負け
        $result_message .= '<span class="lose">**あなたの負けです...**</span>';
    } else { // $diff === 2
        // 2 は (0-1+3)%3=2, (1-2+3)%3=2, (2-0+3)%3=2 の場合で、すべてプレイヤーの勝ち
        $result_message .= '<span class="win">**あなたの勝ちです！**</span>';
    }
    
    // 結果に再挑戦ボタンを追加
    $result_message .= '<br><br><a href="janken.php" class="reset-button">もう一度遊ぶ</a>';

} else {
    // 最初のアクセス時またはリセット時
    $result_message = '下のボタンから手を選んでください。';
}

// ------------------------------------
// 3. HTMLの出力
// ------------------------------------
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>人間対コンピュータ じゃんけんゲーム</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        .result {
            margin: 20px 0;
            padding: 15px;
            border: 2px solid #ccc;
            border-radius: 5px;
            min-height: 80px;
            background-color: #e9ecef;
        }
        .win {
            color: white;
            background-color: #28a745; /* 緑 */
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .lose {
            color: white;
            background-color: #dc3545; /* 赤 */
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .draw {
            color: white;
            background-color: #ffc107; /* 黄 */
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .janken-form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            margin: 5px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s;
        }
        .janken-form button:hover {
            background-color: #0056b3;
        }
        .janken-form button:nth-child(2) { /* チョキ */
            background-color: #6c757d;
        }
        .janken-form button:nth-child(2):hover {
            background-color: #5a6268;
        }
        .janken-form button:nth-child(3) { /* パー */
            background-color: #17a2b8;
        }
        .janken-form button:nth-child(3):hover {
            background-color: #138496;
        }
        .reset-button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .reset-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✊✌️✋ じゃんけんゲーム 🤖</h1>

        <div class="result">
            <?php echo $result_message; ?>
        </div>
        
        <?php if ($player_hand === null): ?>
            <form method="POST" action="janken.php" class="janken-form">
                <p>あなたの手を決めてください。</p>
                
                <button type="submit" name="player_hand" value="<?php echo ROCK; ?>">
                    グー (✊)
                </button>

                <button type="submit" name="player_hand" value="<?php echo SCISSORS; ?>">
                    チョキ (✌️)
                </button>

                <button type="submit" name="player_hand" value="<?php echo PAPER; ?>">
                    パー (✋)
                </button>
            </form>
        <?php endif; ?>

    </div>
</body>
</html>
