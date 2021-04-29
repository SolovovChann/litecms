<?php

use function Litecms\Assets\debug;
include_once 'components/head.php';

?>

<div>
	<? if (!empty ($args)): ?>
		Вы ввели аргументы:
		<ol>
			<? foreach ($args as $item): ?>
				<li>(<?= gettype ($item) ?>): <?= $item ?></li>
			<? endforeach; ?>
		</ol>
	<? else: ?>
		Вы не ввели ни одного аргумента. Перейдите на URL <a href="/test/dump/">/test/dump/</a> и впишите аргументы в адресную строку используя '/' для разделения.
	<? endif; ?>
</div>