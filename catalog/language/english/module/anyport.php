<?php
// Heading
$_['heading_title']       = 'AnyPort - Cloud Backup/Restore';

// Text
$_['text_module']         = 'Modules';
$_['text_success']        = 'Success: You have modified module AnyPort!';
$_['text_success_activation']        = 'ACTIVATED: You have successfully activated AnyPort!';
$_['text_content_top']    = 'Content Top';
$_['text_content_bottom'] = 'Content Bottom';
$_['text_column_left']    = 'Column Left';
$_['text_column_right']   = 'Column Right';
$_['text_pick_destination_and_tables'] = 'Please pick a backup destination and/or tables and/or folders.';
$_['text_pick_source_and_file'] = 'Please pick a restore source and file.';
$_['text_feature_unsupported'] = 'This feature is supported only for OpenCart version {VERSION}';

// Entry
$_['entry_code']          = 'AnyPort status:<br /><span class="help">Enable or disable AnyPort</span>';
$_['entry_layouts_active']          = 'Activated on:<br /><span class="help">Choose which pages AnyPort to be active</span>';
$_['entry_highlightcolor']        = 'Highlight color:<br /><span class="help">This is the color the keyword in the results highlights in.<br/><br/><em>Examples: red, blue, #F7FF8C</em></span>';

// Success
$_['restore_success'] 	  = 'Data was successfully restored!';
$_['restore_success_warnings'] = 'Data was restored, with {COUNT} warnings during the process. <a href="javascript:void(0)" class="showWarnings">Show warnings</a>';

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify module AnyPort!';
$_['error_code']          = 'Highlight color is required';

// Dropbox
$_['dropbox_success']	  = 'You have successfully uploaded to Dropbox!';
$_['dropbox_failure']	  = 'Could not upload to Dropbox.';
$_['dropbox_redirect']	  = 'The file was too big to upload to Dropbox. Please go to <a target="_blank" href="https://www.dropbox.com/home/{FOLDER_ID}">Dropbox</a> and upload the file from there.';
$_['dropbox_not_logged_in'] = 'You are not logged into Dropbox.';
$_['dropbox_access_token_expired'] = 'Please refresh your Dropbox connection.';
$_['dropbox_active'] = 'You are logged into Dropbox.';

// Google Drive
$_['google_drive_success']	  = 'You have successfully uploaded to Google Drive!';
$_['google_drive_failure']	  = 'Could not upload to Google Drive.';
$_['google_drive_redirect']   = 'The file was too big to upload to Google Drive. Please go to <a target="_blank" href="https://drive.google.com/#{FOLDER_ID}">Google Drive</a> and upload the file from there.';

// SkyDrive
$_['sky_drive_success']	  = 'You have successfully uploaded to SkyDrive!';
$_['sky_drive_failure']	  = 'Could not upload to SkyDrive.';	
$_['skydrive_redirect']   = 'The file was too big to upload to SkyDrive. Please go to <a target="_blank" href="https://skydrive.live.com/?id={FOLDER_ID}">SkyDrive</a> and upload the file from there.';

// Exceptions
$_['anyport_no_file']	  = 'No file selected.';
$_['anyport_no_access_token']= 'No access token set.';
$_['anyport_no_auth_code']= 'No auth code was found.';
$_['anyport_temp_dir_error'] = 'Could not create temporary directory.';
$_['anyport_folder_not_string'] = 'The folder is not of type "String".';
$_['anyport_destination_not_string'] = 'The destination path is not of type "String".';
$_['anyport_folder_empty'] = 'The folder is empty.';
$_['anyport_unable_file'] = 'Could not generate file.';
$_['anyport_unable_write_file'] = 'Unable to write to file.';
$_['anyport_unable_read_file'] = 'Unable to read from file.';
$_['anyport_unable_zip_file'] = 'Could not create zip file.';
$_['anyport_unable_zip_file_open'] = 'Could not open the zip file. Please try again.';
$_['anyport_unable_zip_file_extract'] = 'Could not extract the zip file.';
$_['anyport_unable_cache'] = 'Could not set cache storage method.';
$_['anyport_unable_find_folder'] = 'Could not locate the folder.';
$_['anyport_invalid_data'] = 'Invalid data is passed.';
$_['anyport_invalid_file'] = 'Invalid file.';
$_['anyport_unknown_loading_mode'] = 'Unknown loading mode.';
$_['anyport_no_download_url'] = 'Could not find download URL.';
$_['anyport_mismatch_size'] = 'The resulting file size does not match with the source file size.';
$_['anyport_unable_download'] = 'Could not download file.';
$_['anyport_need_authorize'] = 'Please authorize yourself.';
$_['anyport_curl_disabled'] = 'cURL is disabled. Please enable it.';
$_['anyport_unable_upload'] = 'Could not upload the file.';
$_['anyport_error_while_restoring'] = 'There was an error while restoring the files.';
$_['anyport_file_too_big_download'] = 'The file is too big to download. Please download it manually from <a target="_blank" href="{LINK}">{LINK}</a> and do a restore form your computer.';

?>