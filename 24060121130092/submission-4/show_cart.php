<?php
// File         : show_cart.php
// Deskripsi    : Untuk menambahkan item ke shopping cart dan menampilkan isi shopping cart

// TODO 1: Tuliskan session
session_start();
$id = !empty($_GET['id']) ? $_GET['id'] : "";
if ($id != "") {
    //jika $_SESSION['cart'] belum ada, maka inisialisasi dengan array kosong
    //$_SESSION['cart'] merupakan array assosiatif
    //indeksnya berisi isbn buku yang dipilih
    //value-nya berisi jumlah(qty) dari buku dengan isbn tersebut
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    //jika $_SESSION['cart'] dengan indeks isbn buku yang dipilih sudah ada, tambahkan valuenya dengan 1
    //jika belum ada, buat elemen baru dengan indeks tersebut dan set nilainya dengan 1
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
}
?>

<?php include('./header.php') ?>
<br>
<div class="card mt-4">
    <div class="card-header">Shopping Cart</div>
    <div class="card-body">
        <br>
        <table class="table table-striped">
            <tr>
                <th>ISBN</th>
                <th>Author</th>
                <th>Title</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Price * Qty</th>
            </tr>
            <?php

            // TODO 2: Tuliskan dan eksekusi query
            require_once 'lib/db_login.php';
            $sum_qty = 0; //inisialisasi total item di shopping cart
            $sum_price = 0; //inisialisasi total price di shopping cart
            if (isset($_SESSION['cart']) && is_array([$_SESSION['cart']])) {
                foreach ($_SESSION['cart'] as $id => $qty) {
                    $query = "SELECT * FROM books WHERE isbn= '" . $id . "' ";
                    $result = $db->query($query);
                    if (!$result) {
                        die("Could not query the database: <br>" . $db->error . "<br>Query:" . $query);
                    }
                    while ($row = $result->fetch_object()) {
                        echo '<tr>';
                        echo '<td>' . $row->isbn . '</td>';
                        echo '<td>' . $row->author . '</td>';
                        echo '<td>' . $row->title . '</td>';
                        echo '<td>' . $row->price . '</td>';
                        echo '<td>' . $qty . '</td>';
                        echo '<td>' . $row->price * $qty . '</td>';
                        echo '</tr>';
                        $sum_qty += $qty;
                        $sum_price = $sum_price + ($row->price * $qty);
                    }
                }
                echo '<tr><td></td><td></td><td></td><td></td><td>' . $sum_qty . '</td><td>' . $sum_price . '</td></tr>';
            } else {
                echo '<tr><td colspan="6" align="center">There is no item in shopping cart</td></tr>';
            }
            echo '</table>';
            echo '<br/>';
            
            // TODO 3: Panggil total item
            if(isset($result)){
                echo 'Total Rows= ' . $result->num_rows;
                $result->free();
            }
            
            $db->close();    
            ?>
        <br/><br/>
        <a class="btn btn-primary" href="view_books.php">Continue Shopping</a>
        <a class="btn btn-danger" href="delete_cart.php">Empty Cart</a>
    </div>
</div>
<?php include('./footer.php') ?>