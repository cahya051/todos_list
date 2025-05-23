<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
session_start();

// Simpan data ke session jika belum ada
if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}

// Tambah todo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tugas'], $_POST['deadline'])) {
    $_SESSION['todos'][] = [
        'tugas' => $_POST['tugas'],
        'deadline' => $_POST['deadline'],
        'selesai' => false
    ];
    header('Location: index.php');
    exit();
}

// Tandai selesai
if (isset($_GET['selesai'])) {
    $_SESSION['todos'][$_GET['selesai']]['selesai'] = true;
    header('Location: index.php');
    exit();
}

// Hapus todo
if (isset($_GET['hapus'])) {
    array_splice($_SESSION['todos'], $_GET['hapus'], 1);
    header('Location: index.php');
    exit();
}

// Edit todo
if (isset($_POST['edit_index'])) {
    $_SESSION['todos'][$_POST['edit_index']]['tugas'] = $_POST['edit_tugas'];
    $_SESSION['todos'][$_POST['edit_index']]['deadline'] = $_POST['edit_deadline'];
    header('Location: index.php');
    exit();
}

$today = date('Y-m-d');
$searchKeyword = strtolower($_GET['search'] ?? '');
?>

<!DOCTYPE html>
<html>
<head>
    <title>ToDo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="">
   
</head>
<body>
    
<div class="container mt-5">
    <div class="text-end">
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
    <h2 class="text-center mb-4" id="judul-todolist">
     <i class="bi bi-journal-text"></i> ToDo List
    </h2>


    <!-- Form Pencarian -->
    <form method="GET" class="mb-3 row g-2">
        <div class="col-md-9">
            <input type="text" name="search" class="form-control" placeholder="Cari todo..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn custom-btn w-100">Cari</button>
        </div>
    </form>

    <!-- Form Tambah -->
    <form method="POST" class="row g-2 mb-4">
        <div class="col-md-8">
            <input type="text" name="tugas" class="form-control" placeholder="Apa yang ingin kamu kerjakan?" required>
        </div>
        <div class="col-md-2">
            <input type="date" name="deadline" class="form-control" required>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn custom-btn w-100">Tambah</button>

        </div>
    </form>

    <?php foreach ($_SESSION['todos'] as $index => $todo): 
        if ($searchKeyword && strpos(strtolower($todo['tugas']), $searchKeyword) === false) continue;
        $isLate = !$todo['selesai'] && $todo['deadline'] < $today;
        $cardClass = $todo['selesai'] ? 'bg-success text-white' : ($isLate ? 'bg-danger bg-opacity-25' : 'bg-white');
    ?>
        <div class="card mb-2 <?= $cardClass ?>">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <strong <?= $todo['selesai'] ? 'style="text-decoration: line-through;"' : '' ?>><?= htmlspecialchars($todo['tugas']) ?></strong><br>
                    <small class="text-muted">Deadline: <?= $todo['deadline'] ?></small>
                    <?php if ($isLate): ?>
                        <span class="badge bg-danger ms-2">Terlambat</span>
                    <?php endif; ?>
                </div>
                <div>
                    <!-- Edit Button Trigger -->
                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $index ?>">
                        ✏
                    </button>
                    <a href="?selesai=<?= $index ?>" class="btn btn-sm btn-outline-success">✅</a>
                    <a href="?hapus=<?= $index ?>" class="btn btn-sm btn-outline-danger">❌</a>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal<?= $index ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Todo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="edit_index" value="<?= $index ?>">
                            <div class="mb-3">
                                <label class="form-label">Tugas</label>
                                <input type="text" class="form-control" name="edit_tugas" value="<?= htmlspecialchars($todo['tugas']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deadline</label>
                                <input type="date" class="form-control" name="edit_deadline" value="<?= $todo['deadline'] ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>