<?php

@header( 'Content-Type: application/json' );
@readfile( 'storage' . DIRECTORY_SEPARATOR . 'status.js' );
