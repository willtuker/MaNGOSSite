<div class="">
    <?php if (!empty($realms)) : ?>
        <?php foreach ($realms as $realm) : ?>
            <h2><?= $realm['name'] ?></h2>
            <h3><i class="fas fa-server" style="color: <?= $realm['state'] == 0 ? "green" : "red"  ?>;"></i> <?= $realm['state'] == 0 ? "Online" : "Offline"  ?></h3>
            <h2>Players: <?= $realm['online'] ?> / <?= $realm['total'] ?></h2>
        <?php endforeach; ?>
    <?php endif; ?>
</div>