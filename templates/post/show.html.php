<h3>The post</h3>
<div class="border border-dark">
<h3><?= $post->getTitle() ?></h3>
<p><?= $post->getContent() ?></p>

<a href="/posts" class="btn btn-primary">Return</a>
<a href="/post/update?id=<?= $post->getId() ?>" class="btn btn-">Edit</a>
<a href="/post/delete?id=<?= $post->getId() ?>" class="btn btn-">Delete</a>
</div>

<div class="border border-dark">
    <?php foreach ($post->getComments() as $comment) : ?>

    <p><strong><?= $comment->getContent() ?></strong></p>

    <?php endforeach; ?>
</div>

<div class="border border-dark">

    <form class="form form-control" action="/comment/new" method="post">
        <input type="text" name="content" class="form-control:text">
        <input type="hidden" name="postId" value="<?=$post->getId() ?>">
        <button type="submit">Comment</button>

    </form>

</div>