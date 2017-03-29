<?php
require 'vendor/autoload.php';
\houdunwang\qrcode\QrCode::width( 500 )
                         ->height( 500 )
                         ->backColor( 255, 255, 255 )
                         ->foreColor( 0, 0, 0 )
                         ->save( 'http://houdunren.com','qr.png' );

