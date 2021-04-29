<? include "components/head.php" ?>
<? include "components/header.php" ?>

<h1><?= $title ?></h1>

<? if (!empty (\Litecms\Config\Models)): ?>
<p>
	Существующие модели:
</p>
	<ol>
		<? foreach (\Litecms\Config\Models as $model): ?>
			<li>
				<a href="model/<?= end (explode ('\\', $model)) ?>">
					<?= $model::$verbosePlural ?>
				</a>
			</li>
		<? endforeach; ?>
	</ol>
<? endif; ?>

<? include "components/footer.php" ?>