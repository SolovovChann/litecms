<? include 'components/head.php'; ?>

<h1>Whoops, something gone wrong!</h1>
<p>Let's <a href="/">go back</a> to homepage.</p>
<? if (\Litecms\Config\Debug === true and !empty ($_GET)): ?>
	<p><strong>Debug</strong>: <?= $_GET['msg'] ?></p>
<? endif; ?>
<p>If you see this warning too often, please <a href="https://github.com/SolovovChann">contact us</a>. We can support you in solving that problem.</p>