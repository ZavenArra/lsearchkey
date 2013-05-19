<?php

Route::set('lkeysearch', 'lkeysearch/<action>(/<param1>(/<param2>))', array( ))
->defaults(
	array(
		'controller' => 'lkeysearch',
	));
