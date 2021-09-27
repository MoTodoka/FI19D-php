<?php
$title = "Database_Test";
$form = new Form($_POST);

function print_table($data_array, $header)
{
    $printable_result = "<table>";
    foreach ($header as $header_column) {
        $printable_result .= "<th>";
        $printable_result .= $header_column;
        $printable_result .= "</th>";
    }
    foreach ($data_array as $data_row) {
        $printable_result .= "<tr>";
        foreach ($data_row as $data_column) {
            $printable_result .= "<td>";
            $printable_result .= $data_column;
            $printable_result .= "</td>";
        }
        $printable_result .= "</tr>";
    }
    $printable_result .= "</table>";
    return $printable_result;
}

class Form
{
    private $form_uid = "";
    private $form_name = "";
    private $form_number = "";
    private $form_notification = "";

    function __construct($post)
    {
        if ($post) {
            $this->form_uid = $post["uid"];
            $this->form_name = $post["name"];
            $this->form_number = $post["number"];
            $this->execute($post);
        }
    }

    function get_form_uid()
    {
        return $this->form_uid;
    }

    function get_form_name()
    {
        return $this->form_name;
    }

    function get_form_number()
    {
        return $this->form_number;
    }

    function get_form_notification()
    {
        return $this->form_notification;
    }

    function execute($post)
    {
        $database = new Database();
        if (isset($post["insert"])) {
            if ($this->is_insertable()) {
                if (!$database->insert($post["name"], $post["number"])) {
                    $this->form_notification = $database->get_error();
                }
            } else {
                $this->form_notification = "Zum Einfügen müssen alle Informationen außer der UID angegeben werden!";
            }
        } else if (isset($post["delete"])) {
            if ($this->is_deletable()) {
                if (!$database->delete_by_uid($post["uid"])) {
                    $this->form_notification = $database->get_error();
                }
            } else {
                $this->form_notification = "Zum Löschen bitte eine UID angeben!";
            }
        } else if (isset($post["update"])) {
            if ($this->is_updatable()) {
                if (!$database->update($post["uid"], $post["name"], $post["number"])) {
                    $this->form_notification = $database->get_error();
                }
            } else {
                $this->form_notification = "Für das Update bitte alle Felder füllen!";
            }
        } else if (isset($post["read"])) {
            if ($this->is_readable()) {
                $result = $database->select_by_uid($post["uid"]);
                if ($result) {
                    $this->set_form_values($result);
                } else {
                    $this->form_notification = sprintf("Datensatz %s nicht gefunden!", $post["uid"]);
                }
            } else {
                $this->form_notification = "Zum Lesen bitte eine UID angeben!";
            }
        }
    }

    function set_form_values($data_array)
    {
        $this->form_uid = $data_array[0][0];
        $this->form_name = $data_array[0][1];
        $this->form_number = $data_array[0][2];
    }

    function is_readable()
    {
        return $this->form_uid != "";
    }

    function is_insertable()
    {
        return
            $this->form_name != "" &&
            $this->form_number != "";
    }

    function is_updatable()
    {
        return
            $this->form_uid != "" &&
            $this->form_name != "" &&
            $this->form_number != "";
    }

    function is_deletable()
    {
        return $this->form_uid != "";
    }
}

class Database
{
    private $sql_connection;
    private $error = "";

    function __construct()
    {
        $sql_hostname = "127.0.0.1";
        $sql_user = "web_user";
        $sql_passwd = "9flZGpeD5cZm1XvI";
        $sql_database = "uebungsaufgaben";

        $this->sql_connection = new mysqli(
            $sql_hostname,
            $sql_user,
            $sql_passwd,
            $sql_database
        );

        if ($this->sql_connection->connect_error) {
            die("Connection failed: " . $this->sql_connection->connect_error);
        }
    }

    function get_error()
    {
        return $this->error;
    }

    function __destruct()
    {
        $this->sql_connection->close();
    }

    function select_all()
    {
        $query = $this->sql_connection->prepare("SELECT * FROM aufgabe_2");
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_all();
    }

    function select_by_uid($uid)
    {
        $query = $this->sql_connection->prepare("SELECT * FROM aufgabe_2 WHERE uid = ?;");
        $query->bind_param("i", $uid);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_all();
    }

    function delete_by_uid($uid)
    {
        $query = $this->sql_connection->prepare("DELETE FROM aufgabe_2 WHERE uid = ?;");
        $query->bind_param("i", $uid);
        $result = $query->execute();
        if (!$result) {
            $this->error = $query->error;
        }
        if (!$query->affected_rows) {
            $this->error = "Keine Zeile zum Löschen gefunden!";
            $result = false;
        }
        return $result;
    }

    function insert($name, $number)
    {
        $query = $this->sql_connection->prepare("INSERT INTO aufgabe_2 (name, number) VALUE (?, ?);");
        $query->bind_param("si", $name, $number);
        $result = $query->execute();
        if (!$result) {
            $this->error = $query->error;
        }
        if (!$query->affected_rows) {
            $this->error = "Keine Zeile zum Löschen gefunden!";
            $result = false;
        }
        return $result;
    }

    function update($uid, $name, $number)
    {
        $result = false;
        if ($this->select_by_uid($uid)) {
            $query = $this->sql_connection->prepare("UPDATE aufgabe_2 SET name = ?, number = ? WHERE uid = ?;");
            $query->bind_param("sii", $name, $number, $uid);
            $result = $query->execute();
            if (!$result) {
                $this->error = $query->error;
            }
            if (!$query->affected_rows) {
                $this->error = "Keine Zeile zum Updaten gefunden!";
                $result = false;
            }
        }
        return $result;
    }
}

?>

<!DOCTYPE html>
<html lang="de" xmlns="http://www.w3.org/1999/html">

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
<H1><?php echo $title; ?></H1>
<H2>Test Data</H2>
<?php echo print_table((new Database)->select_all(), ["UID", "Name", "Nummer"]); ?>
<H2>Formular</H2>
<form method="post">
    <label>
        UID: <input type="number" name="uid" value="<?php echo $form->get_form_uid(); ?>">
    </label>
    <label>
        Name: <input type="text" name="name" value="<?php echo $form->get_form_name(); ?>">
    </label>
    <label>
        Nummer: <input type="number" name="number" value="<?php echo $form->get_form_number(); ?>">
    </label>
    <input type="submit" value="Lesen" name="read">
    <input type="submit" value="Hinzufügen" name="insert">
    <input type="submit" value="Ändern" name="update">
    <input type="submit" value="Löschen" name="delete">
</form>
<H4><?php echo $form->get_form_notification(); ?></H4>
</body>

</html>