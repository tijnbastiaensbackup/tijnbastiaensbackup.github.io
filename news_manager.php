<?php
/* ==============================
   📰 Nieuws Manager PHP Script
   ============================== */

// 📁 Configuratie
$dir = "news_images/"; // map voor nieuwsfoto's
$newsFile = "news.json";

// Maak map aan als deze niet bestaat
if(!is_dir($dir)) mkdir($dir, 0755, true);

// Laad huidige nieuwsitems
$news = [];
if(file_exists($newsFile)){
    $news = json_decode(file_get_contents($newsFile), true);
}

// 📝 Logfunctie
function addLog($msg){
    $time = date("Y-m-d H:i:s");
    file_put_contents("developer_logs.txt","[$time] $msg\n",FILE_APPEND);
}

// ⬆ Nieuws toevoegen
if(isset($_POST['title'], $_POST['text'])){
    $title = $_POST['title'];
    $text = $_POST['text'];
    $date = date("Y-m-d");

    $images = [];
    if(isset($_FILES['images'])){
        foreach($_FILES['images']['tmp_name'] as $i => $tmp){
            if($_FILES['images']['error'][$i] === 0){
                $name = time() . "_" . basename($_FILES['images']['name'][$i]);
                move_uploaded_file($tmp, $dir . $name);
                $images[] = $dir . $name;
            }
        }
    }

    $news[] = [
        "title"=>$title,
        "text"=>$text,
        "date"=>$date,
        "images"=>$images
    ];

    file_put_contents($newsFile, json_encode($news, JSON_PRETTY_PRINT));
    addLog("📰 Nieuws toegevoegd: $title");

    header("Location: news_manager.php");
    exit;
}

// 🗑 Nieuws verwijderen
if(isset($_POST['delete'])){
    $idx = $_POST['delete'];
    if(isset($news[$idx])){
        if(isset($news[$idx]['images'])){
            foreach($news[$idx]['images'] as $img){
                if(file_exists($img)) unlink($img);
            }
        }
        addLog("🗑 Nieuws verwijderd: " . $news[$idx]['title']);
        array_splice($news, $idx, 1);
        file_put_contents($newsFile, json_encode($news, JSON_PRETTY_PRINT));
    }
    header("Location: news_manager.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Nieuws Manager</title>
<style>
body{font-family:Arial,sans-serif;background:#0f5c66;color:#fff;padding:20px;}
h1{color:#f5d442;}
h2{margin-top:30px;color:#f5d442;}
a,button{text-decoration:none;}
button{padding:8px 12px;border:none;border-radius:6px;cursor:pointer;}
.delete-btn{background:#e60000;color:#fff;}
.delete-btn:hover{background:#ff3333;}
.upload-btn{background:#0a671c;color:#fff;}
.upload-btn:hover{background:#17a135;}
.news-box{background:#176d77;padding:15px;border-radius:12px;margin-bottom:15px;}
.news-box img{width:100px;height:70px;object-fit:cover;margin-right:5px;border-radius:6px;}

/* TEXT WRAP EN MULTILINE VOOR NIEUWS */
.news-box p {
    white-space: pre-wrap;
    word-wrap: break-word;
    line-height: 1.5;
    margin-top: 10px;
}

input[type=text], textarea{width:400px;padding:8px;border-radius:6px;border:none;margin-bottom:10px;}
</style>
</head>
<body>

<h1>📰 Nieuws Manager</h1>

<!-- 🔙 Terug naar Developer Panel -->
<a href="foot.html"><button>⬅ Terug naar Developer Panel</button></a>

<!-- Bestaande Nieuwsitems -->
<h2>Huidige Nieuwsitems</h2>
<?php foreach(array_reverse($news) as $i => $item): ?>
<div class="news-box">
    <h3><?php echo htmlspecialchars($item['title']); ?> - <?php echo $item['date']; ?></h3>
    <p><?php echo nl2br(htmlspecialchars($item['text'])); ?></p>
    <?php if(!empty($item['images'])): ?>
        <?php foreach($item['images'] as $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Nieuws foto">
        <?php endforeach; ?>
    <?php endif; ?>
    <form method="post" style="margin-top:10px;">
        <input type="hidden" name="delete" value="<?php echo count($news)-1-$i ?>">
        <button class="delete-btn" type="submit">Verwijder Nieuws</button>
    </form>
</div>
<?php endforeach; ?>

<!-- Nieuws Toevoegen -->
<h2>📤 Nieuw Nieuws Toevoegen</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Titel" required><br>
    <textarea name="text" placeholder="Tekst" required rows="6"></textarea><br>
    <input type="file" name="images[]" multiple><br><br>
    <button class="upload-btn" type="submit">Toevoegen</button>
</form>

</body>
</html>
