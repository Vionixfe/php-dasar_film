<?php
$title="Edit Film";
require "layout/header.php";

session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    echo "<script>
            alert('Please login first');
            document.location.href = 'login.php';
          </script>";
    exit;
}
// Periksa apakah user sudah login dan role adalah admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Akses ditolak! Hanya admin yang bisa mengedit data.');
            document.location.href = 'films.php';
          </script>";
    exit;
}


$id = $_GET['id']; 
$film = query("SELECT * FROM films WHERE id_film = $id")[0];
$categories = query("SELECT * FROM categories ORDER BY created_at DESC");

if (isset($_POST['update'])) {
    if (update_film($_POST) > 0) {
        echo "<script>
            alert('Film has been updated');
            document.location.href = 'films.php';
        </script>";
    }
    else {
        echo "<script>
            alert('Film has not been updated');
            document.location.href = 'films-edit.php?id=$id';
        </script>";
    }
}
?>

<!-- main -->
<main class="p-4">
    <div class="containter">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-pencil"></i>
                        <?= $title; ?>
                    </div>

                    <div class="card-body shadow-sm">
                        <form action="" method="post">
                        <input type="hidden" name="id_film" value="<?= $film['id_film']; ?>">
                            <div class="mb-3">
                                <label for="url">URL<small>(copy from youtube)</small></label>
                                <input type="text" class="form-control" id="url" name="url" value="<?= $film['url']; ?>" required>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?= $film['title']; ?>" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="slug">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="<?= $film['slug']; ?>" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control"><?= $film['description']; ?></textarea>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="release_date">Release Date</label>
                                    <input type="date" class="form-control" id="release_date" name="release_date" value="<?= $film['release_date']; ?>" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="studio">Studio</label>
                                    <input type="text" class="form-control" id="studio" name="studio" value="<?= $film['studio']; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="is_private">Private</label>
                                    <select name="is_private" id="is_private" class="form-select" required>
                                        <option value="" hidden>---Select---</option>
                                        <option value="0" <?= $film['is_private'] == 0 ? 'selected' : ''; ?>>Public</option>
                                        <option value="1" <?= $film['is_private'] == 1 ? 'selected' : ''; ?>>Private</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="" hidden>---Select---</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category['id_category']; ?>" <?= $film['category_id'] == $category['id_category'] ? 'selected' : ''; ?>><?= $category['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="float-end">
                                <button type="submit" class="btn btn-primary" name="update"><i class="bi bi-upload"></i> Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script src="assets/js/helper.js"></script>

<script>
    $(document).ready(function() {
        $("#title").on("input", function() {
            $('#slug').val(slugify($(this).val()));
        })
    });
</script>

<?php require "layout/footer.php"; ?>
