<?php
$user = 'a01468396';
$pass = '7123042';
$database = 'lab';
// establish database connection
$conn = oci_connect($user, $pass, $database);
if (!$conn) exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Auftrags</title>
</head>

<body>

<div>
    <form id='searchform' action='auftrag.php' method='get'>
        <a href='auftrag.php'>Alle Auftrage</a> ---
        Suche nach Produkten Liste:
        <input id='search' name='search' type='text' size='20'
               value='<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>'/>
        <input id='submit' type='submit' value='Los!'/>
        <a href='produkt.php'>Alle Produkte</a> |
        <a href='klient.php'>Alle Klients</a> |
        <a href='bezahlun.php'>Alle Bezahlungen</a> |
    </form>
</div>


<div>
    <form id='insertform' action='auftrag.php' method='get'>
        Neue Auftrag einfuegen:
        <table style='border: 1px solid #DDDDDD'>
            <thead>
            <tr>
                <th>Auftragid</th>
                <th>Preis Summe</th>
                <th>Produkten Liste</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input id='Auftragid' name='Auftragid' type='number' size='10'
                           value='<?php if (isset($_GET['Auftragid'])) echo $_GET['Auftragid']; ?>' />
                </td>
                <td>
                    <input id='preissumme' name='preissumme' type='number' size='10'
                           value='<?php if (isset($_GET['preissumme'])) echo $_GET['preissumme']; ?>' />
                </td>
                <td>
                    <input id='ProduktenListe' name='ProduktenListe' type='text' size='20'
                           value='<?php if (isset($_GET['ProduktenListe'])) echo $_GET['ProduktenListe']; ?>' />
                </td>
            </tr>
            </tbody>
        </table>
        <input id='submit' type='submit' value='Insert!'/>
    </form>
</div>

<?php
//Handle insert
if (isset($_GET['Auftragid'])) {
    //Prepare insert statementd
    $sql = "INSERT INTO Auftrag VALUES(" . $_GET['Auftragid'] . ",'" . $_GET['preissumme'] . "','" . $_GET['ProduktenListe'] . "') ";
    //Parse and execute statement
    $insert = oci_parse($conn, $sql);
    oci_execute($insert);
    $conn_err = oci_error($conn);
    $insert_err = oci_error($insert);
    if (!$conn_err & !$insert_err) {
        print("Successfully inserted");
        print("<br>");
    } //Print potential errors and warnings
    else {
        print($conn_err);
        print_r($insert_err);
        print("<br>");
    }
    oci_free_statement($insert);
}
?>

<?php
// check if search view of list view
//  «oci_fetch_all», «oci_fetch_array», «oci_fetch_object», «oci_fetch_row» oder «oci_fetch»
if (isset($_GET['search'])) {
    $sql = "SELECT * FROM AUFTRAG WHERE ProduktenListe LIKE '%" . $_GET['search'] . "%'";
} else {
    $sql = "SELECT * FROM AUFTRAG ORDER BY AUFTRAGID ASC ";
}

// execute sql statement
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<table style='border: 1px solid #DDDDDD'>
    <thead>
    <tr>
        <th>Auftragid</th>
        <th>Preis Summe</th>
        <th>Produkten Liste</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // fetch rows of the executed sql query
    while ($row = oci_fetch_assoc($stmt)) {
        echo "<tr>";
        echo "<td>" . $row['AUFTRAGID'] . "</td>";
        echo "<td>" . $row['PREISSUMME'] . "</td>";
        echo "<td>" . $row['PRODUKTENLISTE'] . "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> Person(en) gefunden!</div>

<?php
// clean up connections
oci_free_statement($stmt);
oci_close($conn);
?>

</body>
</html>