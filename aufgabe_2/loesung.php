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
    private $post = null;
    private $form_uid = "";
    private $form_name = "";
    private $form_number = "";
    private $form_notification = "";

    function __construct($post)
    {
        if ($post) {
            $this->post = $post;
            $this->form_uid = $post["uid"];
            $this->form_name = $post["name"];
            $this->form_number = $post["number"];
            $this->execute();
        }
    }

    function get_form_uid(){
        return $this->form_uid;
    }

    function get_form_name(){
        return $this->form_name;
    }

    function get_form_number(){
        return $this->form_number;
    }

    function get_form_notification(){
        return $this->form_notification;
    }

    function has_post()
    {
        return $this->post != null;
    }

    function execute()
    {
        if ($this->post == null) {
            return;
        } else if (isset($this->post["insert"])) {
            if ($this->is_insertable()) {
                (new Database)->insert($this->post["name"], $this->post["number"]);
            } else {
                $this->form_notification = "Zum Einfügen müssen alle Informationen außer der UID angegeben werden!";
            }
        } else if (isset($this->post["delete"])) {
            if ($this->is_deletable()) {
                (new Database)->delete_by_uid($this->post["uid"]);
            } else {
                $this->form_notification = "Zum Löschen bitte eine UID angeben!";
            }
        } else if (isset($this->post["update"])) {
            if ($this->is_updatable()) {
                (new Database)->update($this->post["uid"], $this->post["name"], $this->post["number"]);
            } else {
                $this->form_notification = "Für das Update bitte alle Felder füllen!";
            }
        } else if (isset($this->post["read"])) {
            if ($this->is_readable()) {
                $this->set_form_values((new Database)->select_by_uid($this->post["uid"]));
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
            $this->form_uid == "" &&
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
    private $sql_hostname = "127.0.0.1";
    private $sql_user = "web_user";
    private $sql_passwd = "9flZGpeD5cZm1XvI";
    private $sql_database = "database_test";

    private $sql_connection;

    function __construct()
    {
        $this->sql_connection =  new mysqli(
            $this->sql_hostname,
            $this->sql_user,
            $this->sql_passwd,
            $this->sql_database
        );

        if ($this->sql_connection->connect_error) {
            die("Connection failed: " . $this->sql_connection->connect_error);
        }
    }

    function __destruct()
    {
        $this->sql_connection->close();
    }

    function select_all()
    {
        $query = $this->sql_connection->prepare("SELECT * FROM table_test");
        $query->execute();
        $result = $query->get_result();
        $data_array = $result->fetch_all();
        return $data_array;
    }

    function select_by_uid($uid)
    {
        $query = $this->sql_connection->prepare("SELECT * FROM table_test WHERE uid = ?;");
        $query->bind_param("i", $uid);
        $query->execute();
        $result = $query->get_result();
        $data_array = $result->fetch_all();
        return $data_array;
    }

    function delete_by_uid($uid)
    {
        $query = $this->sql_connection->prepare("DELETE FROM table_test WHERE uid = ?;");
        $query->bind_param("i", $uid);
        $query->execute();
    }

    function insert($name, $number)
    {
        $query = $this->sql_connection->prepare("INSERT INTO table_test (name, number) VALUE (?, ?);");
        $query->bind_param("si", $name, $number);
        $query->execute();
    }

    function update($uid, $name, $number)
    {
        if ($this->select_by_uid($uid)) {
            $query = $this->sql_connection->prepare("UPDATE table_test SET name = ?, number = ? WHERE uid = ?;");
            $query->bind_param("sii", $name, $number, $uid);
            $query->execute();
        }
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
    <H2>Formuar</H2>
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