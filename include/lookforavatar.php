<?php
/* Get the avatar of the user with uvanetid = $user_id */
function lookforavatar($user_id, $other_directory) {
		/* Allowed extensions */
		$allowed_extensions = array(
			'jpg',
			'gif',
			'png',
			'jpeg',
			'bmp'
		);
		
		// Test if extension is in the list of allowed extensions
		foreach ($allowed_extensions as $value) {
			if (file_exists($other_directory . "uploads/avatars/" . $user_id . "." . $value)) {
				$avatar = BASE."uploads/avatars/" . $user_id . "." . $value;
				break;
			}
			// extension not allowed? show default avatar.
			else
				$avatar = BASE."images/no-avatar.gif";
		}
		return($avatar);
	}
?>