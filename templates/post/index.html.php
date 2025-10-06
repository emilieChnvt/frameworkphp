<h2>The posts</h2>
<?php foreach ($posts as $post): ?>

<h3><?= $post->getTitle() ?></h3>
<p><?= $post->getContent() ?></p>

    <a href="/post/show?id=<?= $post->getId() ?>" class="btn btn-primary">Read</a>



<?php endforeach; ?>
