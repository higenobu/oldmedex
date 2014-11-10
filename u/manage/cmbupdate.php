<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT update patient no</title>
	</head>
	<body>
<a href="index.php?tab=1">goback to main</a>


<br>


<?php

//set patientID into resultwkcmb if patient is registered.

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//0826-2013 
/*
class NiceSSH {
    // SSH Host
    private $ssh_host = '63.240.71.163';
    // SSH Port
    private $ssh_port = 5513;
    // SSH Server Fingerprint
    private $ssh_server_fp = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    // SSH Username
    private $ssh_auth_user = 'CMB';
    // SSH Public Key File
    private $ssh_auth_pub = '/home/medex/.ssh/id_dsa.pub';
    // SSH Private Key File
    private $ssh_auth_priv = '/home/medex/.ssh/id_dsa';
    // SSH Private Key Passphrase (null == no passphrase)
    private $ssh_auth_pass = null;
    // SSH Connection
    private $connection;
   
    public function connect() {
        if (!($this->connection = ssh2_connect($this->ssh_host, $this->ssh_port))) {
            throw new Exception('Cannot connect to server');
        }
        $fingerprint = ssh2_fingerprint($this->connection, SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
        if (strcmp($this->ssh_server_fp, $fingerprint) !== 0) {
            throw new Exception('Unable to verify server identity!');
        }
        if (!ssh2_auth_pubkey_file($this->connection, $this->ssh_auth_user, 
$this->ssh_auth_pub, $this->ssh_auth_priv, $this->ssh_auth_pass)) {
            throw new Exception('Autentication rejected by server');
        }
    }
    public function exec($cmd) {
        if (!($stream = ssh2_exec($this->connection, $cmd))) {
            throw new Exception('SSH command failed');
        }
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
        return $data;
    }
    public function disconnect() {
        $this->exec('echo "EXITING" && exit;');
        $this->connection = null;
    }
    public function __destruct() {
        $this->disconnect();
    }
} 
//connect();
*/

//$output = shell_exec('sh /home/medex/sftptest.sh');

//print_r($output);
//0826-2013

$connection = ssh2_connect('localhost',22,array('hostkey'=>'ssh-dsa'));
//$connection = ssh2_connect('shell.example.com', 22, array('hostkey'=>'ssh-rsa'));

if (ssh2_auth_pubkey_file($connection, 'CMB',
                          '/home/medex/.ssh/id_dsa.pub',
                          '/home/medex/.ssh/id_dsa', 'secret')) {
  echo "Public Key Authentication Successful\n";
} else {
  die('Public Key Authentication Failed');
}
//ssh2_auth_none();

//$sftp = ssh2_sftp($connection);
//print_r($sftp);
//$stream = fopen("ssh2.sftp://$sftp/TEST/*", 'r');

/*
class SFTPConnection
{
    private $connection;
    private $sftp;

    public function __construct($host, $port=5513)
    {
        $this->connection = @ssh2_connect($host, $port);
        if (! $this->connection)
            throw new Exception("Could not connect to $host on port $port.");
    }

    public function login($username, $password)
    {
        if (! @ssh2_auth_password($this->connection, $username, $password))
            throw new Exception("Could not authenticate with username $username " .
                                "and password $password.");

        $this->sftp = @ssh2_sftp($this->connection);
        if (! $this->sftp)
            throw new Exception("Could not initialize SFTP subsystem.");
    }

    public function uploadFile($local_file, $remote_file)
    {
        $sftp = $this->sftp;
        $stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');

        if (! $stream)
            throw new Exception("Could not open file: $remote_file");

        $data_to_send = @file_get_contents($local_file);
        if ($data_to_send === false)
            throw new Exception("Could not open local file: $local_file.");

        if (@fwrite($stream, $data_to_send) === false)
            throw new Exception("Could not send data from file: $local_file.");

        @fclose($stream);
    }
}

try
{
    $sftp = new SFTPConnection("localhost", 5513);
    $sftp->login("CMB", "");
    $sftp->uploadFile("/TEST/*", "/tmp/to_be_received");
}
catch (Exception $e)
{
    echo $e->getMessage() . "\n";
}

*/


//0826-2013

$db = mx_db_connect();

 
$istmt = <<<SQL
	update test_resultwkcmb
set karteno=(select p.pt_no from tbl_patient2 as p
where ptname=p.pt_nm)  
SQL;
 
if (pg_query($db, $istmt)){
print '<p>updated </p>';
}
else {
print '<p > DB access error</p>';
die;
}



 
 

?>


<a href="index.php?tab=1">gpback to main</a>
<br>

	</tbody>
</table>

	</body>
</html>
