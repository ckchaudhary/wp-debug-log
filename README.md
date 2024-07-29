# wp-debug-log
Easy logging feature for development/debugging.
This is in the form of a WordPress plugin.

## Uses
Download the file rb-debug-log.php. That's the plugin. Activate it.
Then call the logging function like below
```
if ( class_exists( '\RBDebugLog' ) ) {
	\RBDebugLog::log( $log_text, 'group-one' );
}
```

This will append the contents of `$log_text` in the file `wp-contents/uploads/group-one-debug.log`

Set `LOGGING_ENABLED` to false when you want to stop logging.
