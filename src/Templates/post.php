<?php include_once __DIR__ . DIRECTORY_SEPARATOR .'header.php'; ?>

<h1>Template for Posts</h1>
<h2>Post name : <?php echo $post; ?></h2>
<h2>Route URL : <?php echo $url; ?></h2>

<ul>
    <?php foreach ($myArray as $val) : ?>
        <li><?php echo $val; ?></li>
    <?php endforeach; ?>
</ul>

<?php if ($testVal === 0) : ?>
    <h1>testVal est nulle</h1>
<?php else : ?>
    <h1>testVal vaut : <?php echo $testVal; ?></h1>
<?php endif; ?>

<?php include_once __DIR__ . DIRECTORY_SEPARATOR .'footer.php'; ?>