<?php
/* ------------------------------
   📁 PORTFOLIO MAP LADEN
------------------------------ */
$dir = "portfolio/";
$files = array_diff(scandir($dir), array('.', '..'));

/* ------------------------------
   📝 LOGGING FUNCTIE
------------------------------ */
function addLog($message){
    $logFile = "developer_logs.txt";
    $time = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$time] $message\n", FILE_APPEND);
}

/* ------------------------------
   🗑 FOTO VERWIJDEREN
------------------------------ */
if(isset($_POST['delete'])){
    $file = $_POST['delete'];
    unlink($dir . $file);

    addLog("📛 FOTO VERWIJDERD: $file via Portfolio Manager");

    header("Location: portfolio_manager.php");
    exit;
}

/* ------------------------------
   ⬆ FOTO UPLOADEN
------------------------------ */
if(isset($_FILES['file']) && $_FILES['file']['error'] === 0){
    $name = $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $dir . $name);

    addLog("📸 FOTO TOEGEVOEGD: $name via Portfolio Manager");

    header("Location: portfolio_manager.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Portfolio Manager</title>

<style>
body {
    margin:0;
    background:#0f5c66;
    font-family:Arial;
    color:#fff;
}

h1 {
    color:#00e6e6;
}

.container {
    max-width:1100px;
    margin:30px auto;
    padding:20px;
}

.back-btn {
    background:#111;
    border:2px solid #0f0;
    padding:10px 20px;
    color:#0f0;
    border-radius:8px;
    cursor:pointer;
    margin-bottom:20px;
}

.back-btn:hover {
    background:#0f0;
    color:#111;
}

.file-box {
    background:#093f46;
    padding:15px;
    border-radius:10px;
    display:flex;
    align-items:center;
    margin-bottom:15px;
}

img {
    width:150px;
    height:auto;
    margin-right:20px;
    border-radius:10px;
}

.delete-btn {
    background:#8b0000;
    color:#fff;
    padding:8px 14px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.delete-btn:hover {
    background:#e60000;
}

.upload-box {
    margin-top:30px;
    padding:20px;
    background:#093f46;
    border-radius:10px;
}

.upload-btn {
    background:#0a671c;
    padding:8px 14px;
    border:none;
    border-radius:6px;
    color:#fff;
    cursor:pointer;
}

.upload-btn:hover {
    background:#17a135;
}
</style>
</head>
<body>

<div class="container">

    <!-- 🔙 TERUG NAAR DEVELOPER PAGINA -->
    <button class="back-btn" onclick="window.location.href='foot.html'">
        ⬅ Terug naar Developer Panel
    </button>

    <h1>Portfolio Manager</h1>

    <h2>📁 Bestanden</h2>

    <?php foreach($files as $file): ?>
    <div class="file-box">
        <img src="portfolio/<?php echo $file; ?>">

        <form method="post">
            <input type="hidden" name="delete" value="<?php echo $file; ?>">
            <button class="delete-btn" type="submit">Verwijder</button>
        </form>
    </div>
    <?php endforeach; ?>

    <!-- ⬆ UPLOAD -->
    <div class="upload-box">
        <h2>📤 Upload nieuwe foto</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button class="upload-btn" type="submit">Upload</button>
        </form>
    </div>

</div>

</body>
</html>
