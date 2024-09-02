<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of eBooks</title>
    <link rel="stylesheet" href="../../assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>List of eBooks</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Cover Image</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Published Year</th>
                    <th>Download Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ebooks)): ?>
                    <tr>
                        <td colspan="9">No eBooks found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ebooks as $ebook): ?>
                        <tr>
                            <td><?= htmlspecialchars($ebook['id']) ?></td>
                            <td><?= htmlspecialchars($ebook['title']) ?></td>
                            <td><?= htmlspecialchars($ebook['author']) ?></td>
                            <td><img src="../../assets/images/<?= htmlspecialchars($ebook['cover_image']) ?>" width="50" height="50" alt="Cover Image"></td>
                            <td><?= htmlspecialchars($ebook['category_name']) ?></td>
                            <td><?= htmlspecialchars($ebook['description']) ?></td>
                            <td><?= htmlspecialchars($ebook['published_year']) ?></td>
                            <td><a href="<?= htmlspecialchars($ebook['download_link']) ?>" target="_blank">Download</a></td>
                            <td><a href="list_ebooks.php?delete_id=<?= htmlspecialchars($ebook['id']) ?>" class="btn btn-danger">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
