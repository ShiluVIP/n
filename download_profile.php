<?php
// Generates a profile pointing back to this exact site, so no domain editing is needed.
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$scheme = $https ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$callback = $scheme . '://' . $host . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/process.php';
$uuid = strtoupper(sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x%04x%04x', random_int(0,65535),random_int(0,65535),random_int(0,65535),random_int(0,4095)|0x4000,random_int(0,16383)|0x8000,random_int(0,65535),random_int(0,65535),random_int(0,65535),random_int(0,65535),random_int(0,65535)));
$xml = '<?xml version="1.0" encoding="UTF-8"?>\n<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">\n<plist version="1.0"><dict>\n<key>PayloadContent</key><dict>\n<key>URL</key><string>'.htmlspecialchars($callback, ENT_XML1).'</string>\n<key>DeviceAttributes</key><array><string>UDID</string><string>DEVICE_NAME</string><string>VERSION</string><string>PRODUCT</string></array>\n</dict>\n<key>PayloadOrganization</key><string>ShiluVIP</string>\n<key>PayloadDisplayName</key><string>استخراج UDID</string>\n<key>PayloadVersion</key><integer>1</integer>\n<key>PayloadUUID</key><string>'.$uuid.'</string>\n<key>PayloadIdentifier</key><string>com.shiluvip.getudid</string>\n<key>PayloadDescription</key><string>ملف مساعد لإظهار UDID الخاص بجهازك</string>\n<key>PayloadType</key><string>Profile Service</string>\n</dict></plist>';
header('Content-Type: application/x-apple-aspen-config; charset=utf-8');
header('Content-Disposition: attachment; filename="ShiluVIP-UDID.mobileconfig"');
header('Cache-Control: no-store');
echo $xml;
