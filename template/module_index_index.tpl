<div class="article">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
<?php if($comments):?>
    Comments:
    <div class="comments">
        <?php foreach($comments as $comment):?>
        <div class="comment">
            <p>
                Nick: <?php print $comment->get('nick')?>
            </p>
            <p>
                Comment: <?php print $comment->get('comment')?>
            </p>

        </div>
        <?php endforeach;?>
    </div>
<?php endif;?>

<?php if($errors):?>
    Errors:
    <div class="errors">
        <?php foreach($errors as $error):?>
        <div class="error">
            <?php print $error ?>
        </div>
        <?php endforeach;?>
    </div>
<?php endif;?>

<div>
    <p>Add new comment:</p>
    <div style="width:300px;">
        <form name="comment" id="comment" method="post">
            <div style="float:left; width:100px;">
                <label for="nick">Nick</label>
                <br />
                <label for="comment">Comment</label>
            </div>
            <div style="float:right; width:200px;">
                <input type="text" name="nick" id="nick" value="<?php print $nick ? $nick : '' ?>">
                <br />
                <textarea name="comment" id="comment" rows="4" cols="50"></textarea>
            </div>
            <div>
                <input type="submit" name="addComment" value="Add comment">
            </div>
        </form>
    </div>
</div>