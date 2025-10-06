<h3>The editing of the post</h3>

<form action="/post/update?id=<?= $post->getId() ?>" method="post" class="form form-control:post">
    <input type="text" name="title" value="<?= $post->getTitle() ?>">
    <input type="text" name="content" value="<?= $post->getContent() ?>">

    <button class="btn btn-success" type="submit">Edit Post</button>
</form>
<a href="/posts" class="btn btn-primary">Return</a>



