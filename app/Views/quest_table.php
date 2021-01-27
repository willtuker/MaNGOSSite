<div style="text-align: center;padding: 5px;">
    Quest with manual feedbacks <?= $totalFeedbacks ?>/<?= $totalQuests ?> (<?= $rateFeedbacks ?> %) <br>
    Quest completed in game <?= $totalDone ?>/<?= $totalQuests ?> (<?= $rateDones ?> %)<br>
    Quest in progress in game <?= $totaleProgress ?><br>
    Quest forced in game <?= $totaleForced ?><br>
</div>
<input id="searchText" class="form-control" placeholder="Search..." value="<?= $searchText ?>" type="text" onkeypress="searchForQuest(event)">
<table class="table table-hover table-dark">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Feedback</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($quests)) : ?>
            <?php foreach ($quests as $quest_item) : ?>
                <tr>
                    <th scope="row"><?= $quest_item['entry']; ?></td>
                    <td><a href="home/quest?id=<?= $quest_item['entry']; ?>"> <?= $quest_item['Title']; ?></a></td>
                    <td><text style="color: #35a30a; margin-right:10px;"><?= $quest_item['ok']; ?></text> - <text style="color: #eb031e; margin-left:10px;"><?= $quest_item['ko']; ?></text></td>
                    <td>
                        <i data-toggle="tooltip" title="Working!" onclick="feedbackOk('<?= $quest_item['entry']; ?>')" class="far fa-thumbs-up" style="color: #35a30a; margin-right:10px;" aria-hidden="true"></i>
                        <i data-toggle="tooltip" title="Not Working!" onclick="feedbackKo('<?= $quest_item['entry']; ?>')" class="far fa-thumbs-down" style="color: #eb031e; margin-right:10px;" aria-hidden="true"></i>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<nav style="width: 100%; overflow-x: scroll;">
    <ul class="pagination">
        <?php
        $prevClass = "";
        $nextClass = "";
        if ($page == 1) {
            $prevClass .= "disabled";
        }
        if ($page == $totalepages) {
            $nextClass .= "disabled";
        }
        ?>
        <li class="page-item <?= $prevClass ?>">
            <a class="page-link" href="##" onclick="getPage('<?= $prevpage ?>')" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php if ($minpage != 1) : ?>
            <li class="page-item"><a class="page-link" href="##" onclick="getPage('1')">1</a></li>
        <?Php endif; ?>
        <?php for ($i = $minpage; $i <= $maxpage; $i++) : ?>
            <?php
            $class = "";
            if ($page == $i)
                $class .= "active";
            ?>
            <li class="page-item <?= $class ?>"><a class="page-link" href="##" onclick="getPage('<?= $i ?>')"><?= $i ?></a></li>
        <?php endfor; ?>
        <?php if ($maxpage != $totalepages) : ?>
            <li class="page-item <?= $class ?>"><a class="page-link" href="##" onclick="getPage('<?= $totalpages ?>')"><?= $totalpages ?></a></li>
        <?Php endif; ?>
        <li class="page-item <?= $prevClass ?>">
            <a class="page-link" href="##" onclick="getPage('<?= $nextpage ?>')" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<?php echo "Total pages: " . $totalpages; ?>

<div>
    <p>Page rendered in {elapsed_time} seconds</p>
</div>

<script>
    function reloadQuestTable() {
        getPage('<?= $page ?>');
    }
</script>