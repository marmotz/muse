<?php if($_session->getFlashBag()->has('error')): ?>
    <div class="message error">
        <?php foreach($_session->getFlashBag()->get('error') as $error): ?>
            <p><?php echo $_($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>