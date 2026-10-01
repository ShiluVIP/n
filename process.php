<?php
// Receives Apple's Profile Service POST and shows only the UDID + basic device info.
function esc($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
$raw = file_get_contents('php://input');
$udid=$name=$version=$product='';
if($raw){
  libxml_use_internal_errors(true);
  $xml=simplexml_load_string($raw);
  if($xml){
    $dict=$xml->dict;
    $keys=$dict->key; $vals=$dict->children();
    // Parse Apple plist dict by key/value pairs.
    $children=$dict->children();
    $pending=null;
    foreach($children as $node){
      if($node->getName()==='key'){$pending=(string)$node;continue;}
      if($pending!==null){$value=(string)$node; if($pending==='UDID')$udid=$value; elseif($pending==='DEVICE_NAME')$name=$value; elseif($pending==='VERSION')$version=$value; elseif($pending==='PRODUCT')$product=$value; $pending=null;}
    }
  }
}
if(!$udid){ http_response_code(400); ?><!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><title>ShiluVIP</title><style>body{font-family:Arial;background:#070b12;color:#fff;text-align:center;padding:60px} .box{max-width:600px;margin:auto;background:#101827;padding:30px;border-radius:22px}a{color:#62a1ff}</style><div class="box"><h2>لم يتم استلام UDID</h2><p>ثبّت ملف التعريف من الإعدادات ثم افتح الموقع من Safari وحاول مرة أخرى.</p><a href="index.php">العودة للموقع</a></div></html><?php exit; }
?><!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>UDID — ShiluVIP</title><style>*{box-sizing:border-box}body{margin:0;background:#05080f;color:#fff;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Arial;padding:24px}.box{max-width:680px;margin:30px auto;background:#0c1422;border:1px solid #24416a;border-radius:26px;padding:28px;box-shadow:0 20px 60px #0008}h1{text-align:center}.ok{text-align:center;color:#6aa6ff}.row{background:#070d17;border:1px solid #1b2d48;border-radius:15px;padding:15px;margin:10px 0}.label{font-size:12px;color:#8ea2bf;margin-bottom:6px}.value{font-weight:700;word-break:break-all;direction:ltr;text-align:left}.copy{display:block;text-align:center;background:#1269ff;color:white;text-decoration:none;padding:15px;border-radius:14px;margin-top:18px;font-weight:800}</style></head><body><div class="box"><div class="ok">✓ تم استخراج بيانات الجهاز</div><h1>UDID جهازك</h1><div class="row"><div class="label">UDID</div><div id="udid" class="value"><?php echo esc($udid); ?></div></div><?php if($name){ ?><div class="row"><div class="label">اسم الجهاز</div><div class="value"><?php echo esc($name); ?></div></div><?php } ?><?php if($product){ ?><div class="row"><div class="label">المنتج</div><div class="value"><?php echo esc($product); ?></div></div><?php } ?><?php if($version){ ?><div class="row"><div class="label">إصدار iOS</div><div class="value"><?php echo esc($version); ?></div></div><?php } ?><a class="copy" href="#" onclick="navigator.clipboard.writeText(document.getElementById('udid').innerText);this.innerText='✓ تم نسخ UDID';return false">نسخ UDID</a></div></body></html>
