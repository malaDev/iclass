<?php

// This should not be a user error so no message
if(!isset($_POST['firstname']))
{
	$message_user = "";
	$message_avatar = "";
	return;
}

if($_POST['firstname'] == '' || $_POST['lastname'] == '' || $_POST['email'] == '')
{
	$message_user = "Please fill in all required fields.";
	$message_avatar = "";
	return;
}

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$firstname = htmlspecialchars(stripslashes($firstname));
$firstname = mysql_real_escape_string($firstname);
$lastname = htmlspecialchars(stripslashes($lastname));
$lastname = mysql_real_escape_string($lastname);
$email = htmlspecialchars(stripslashes($email));
$email = mysql_real_escape_string($email);
$uvanetid = $_POST['uvanetid'];
$type = is_numeric($uvanetid) ? "Student" : "Docent";

$sql = "SELECT * FROM users WHERE uvanetid = '$uvanetid'";
$result = mysql_query($sql);
$rows = mysql_num_rows($result);

if ($rows == 0)
	$query = mysql_query("INSERT INTO users (uvanetid,type,firstname,lastname, email) VALUES ('{$uvanetid}', '{$type}', '{$firstname}', '{$lastname}', '{$email}')");
else
	$query = mysql_query("UPDATE users SET type='{$type}',firstname='{$firstname}',lastname='{$lastname}', email='{$email}' WHERE uvanetid = '{$uvanetid}'");

if ($query && mysql_affected_rows() > 0)
	$message_user = "Settings successfully changed!";
else
	$message_user = "Something went wrong with updating your settings, please try again.";

// now check if an avatar was uploaded, otherwise bail
if($_FILES['avatar']['name'] == "")
{
	$message_avatar = "";
	return;	
}

//maximum allowed size of the file in bytes 
$max = "400000";

// Upload folder (make sure the permissions for this folder are 776)
$target_path = "public/avatars/";

// Get extension 
$extension = explode('.', basename($_FILES['avatar']['name']));
$extension = end($extension);
$target_path = $target_path . $uvanetid . "." . $extension;

// check if image is really an image
$imageinfo = getimagesize($_FILES['avatar']['tmp_name']);

$allowed_extensions = array('jpg', 'gif', 'png', 'bmp', 'jpeg');

// Test if extension is in the list of allowed extensions
$correct_path = False;
foreach ($allowed_extensions as $value)
{
	if ($imageinfo['mime'] == 'image/' . $value)
	{
		foreach ($allowed_extensions as $value2)
		{
			if ($extension == $value2)
			{
				$correct_path = True;
				break;
			}
		}
	}
}

if(!$correct_path)
{
	$message_avatar = "Avatar extension not allowed! Please upload a image.";
	return;
}

if($_FILES['avatar']['size'] > $max)
{
	$message_avatar = "Avatar file size is too big! maximum allowed size is 400KB.";
	return;
}
if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path))
{
	$message_avatar = "Something went wrong with uploading your avatar, please try again.";
	return;
}

//delete all other files
foreach ($allowed_extensions as $value3)
{
	if (file_exists("public/avatars/" . $uvanetid . "." . $value3) && $value3 != $extension)
	{
		unlink("public/avatars/" . $uvanetid . "." . $value3);
		break;
	}
}

$filename = $uvanetid . "." . $extension;
$query = mysql_query("UPDATE users SET avatar='{$filename}' WHERE uvanetid = '{$uvanetid}'");


$message_avatar = "Avatar successfully uploaded!";
$message_user = "";
