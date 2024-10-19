<?php
session_start();
include 'db.php';

// Handle form submission for adding and updating items
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $sql = "UPDATE items SET item_name='$item_name', quantity='$quantity', category='$category' WHERE id=$id";
        $conn->query($sql);
    } else {
        $sql = "INSERT INTO items (user_id, item_name, quantity, category) VALUES ('$user_id', '$item_name', '$quantity', '$category')";
        $conn->query($sql);
    }
    header('Location: index.php');
}

// Handle deletion of items
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM items WHERE id=$id");
}

// Fetch items for the logged-in user
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM items WHERE user_id=$user_id");

// Check if editing
$update = false;
$item_to_edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $item_to_edit = $conn->query("SELECT * FROM items WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Grocery List</title>
</head>
<body class="container mt-5">
<h1>Grocery List</h1>

<form method="POST" class="mb-4">
    <input type="hidden" name="id" value="<?php echo $update ? $item_to_edit['id'] : ''; ?>">
    <div class="form-group">
        <label for="item_name">Item Name</label>
        <input type="text" name="item_name" class="form-control" placeholder="Item Name" value="<?php echo $update ? $item_to_edit['item_name'] : ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" class="form-control" placeholder="Quantity" value="<?php echo $update ? $item_to_edit['quantity'] : ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <input type="text" name="category" class="form-control" placeholder="Category" value="<?php echo $update ? $item_to_edit['category'] : ''; ?>" required>
    </div>
    <button type="submit" name="<?php echo $update ? 'update' : 'add'; ?>" class="btn btn-primary"><?php echo $update ? 'Update Item' : 'Add Item'; ?></button>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td>
                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="logout.php" class="btn btn-secondary">Logout</a>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>